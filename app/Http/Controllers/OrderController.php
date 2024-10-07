<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function cart()
    {
        $orders = Order::with(['orderDetails.product'])->where('status', 0)->get();

        $orderList = $orders->map(function ($order) {
            $totalOrderPrice = $order->orderDetails->sum(function ($detail) {
                return $detail->quantity * $detail->product->price;
            });

            return [
                'order_id' => $order->id,
                'total_order' => $totalOrderPrice,
                'order_details' => $order->orderDetails->map(function ($detail) {
                    return [
                        'product_name' => $detail->product->name,
                        'quantity' => $detail->quantity,
                        'image' => $detail->product->image,
                        'unit_price' => $detail->product->price,
                        'total_price' => $detail->quantity * $detail->product->price,
                    ];
                }),
            ];
        });

        
        return response()->json($orderList);
    }

    public function placeOrder()
    {
        $user_id = auth()->id();

        $order = Order::with(['orderDetails.product'])
            ->where('user_id', $user_id)
            ->where('status', 0)
            ->first();
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng nào chưa hoàn thành cho người dùng này',
            ]);
        }

        $order->status = 1;
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Đặt hàng thành công',
            'order' => $order,
        ]);
    }
    public function userPurchases(Request $request)
    {
        // Lấy danh sách tất cả người dùng
        $users = User::with(['orders.orderDetails.product'])->get();
    
        // Lấy danh sách đơn hàng đã mua với trạng thái là hoàn thành (status = 1)
        $orders = Order::with(['orderDetails.product', 'user'])->where('status', 1)->get();
    
        // Tạo danh sách người dùng đã mua hàng
        $userPurchases = $users->map(function ($user) use ($orders) {
            // Lấy các đơn hàng của người dùng
            $userOrders = $orders->where('user_id', $user->id);
    
            // Tính tổng số lượng sản phẩm mà người dùng đã mua
            $totalQuantity = $userOrders->sum(function ($order) {
                return $order->orderDetails->sum('quantity');
            });
    
            // Tính tổng giá trị đơn hàng của người dùng
            $totalValue = $userOrders->sum(function ($order) {
                return $order->orderDetails->sum(function ($detail) {
                    return $detail->quantity * $detail->product->price;
                });
            });
    
            return [
                'username' => $user->username,
                'email' => $user->email,
                'total_quantity' => $totalQuantity, // 0 nếu không có đơn hàng
                'total_value' => $totalValue, // 0 nếu không có đơn hàng
            ];
        });
    
        // Kiểm tra xem có yêu cầu lọc ra người dùng không có đơn hàng không
        if ($request->input('exclude_purchased') === 'true') {
            $userPurchases = $userPurchases->filter(function ($user) {
                return $user['total_quantity'] == 0;
            })->values();
        }
    
        // Kiểm tra xem có yêu cầu sắp xếp theo số lượng không
        if ($request->input('sort_by_quantity') === 'true') {
            // Sắp xếp danh sách người dùng theo tổng số lượng đã mua giảm dần
            $userPurchases = $userPurchases->sortByDesc('total_quantity')->values();
        }
    
        // Kiểm tra xem có yêu cầu sắp xếp theo tổng giá trị không
        if ($request->input('sort_by_value') === 'true') {
            // Sắp xếp danh sách người dùng theo tổng giá trị đơn hàng giảm dần
            $userPurchases = $userPurchases->sortByDesc('total_value')->values();
        }
    
        // Kiểm tra xem có yêu cầu tìm kiếm không
        $search = $request->input('search');
        if ($search) {
            // Lọc danh sách người dùng theo username hoặc email
            $userPurchases = $userPurchases->filter(function ($user) use ($search) {
                return str_contains($user['username'], $search) || str_contains($user['email'], $search);
            })->values();
        }
    
        // Kiểm tra xem có người dùng nào không
        if ($userPurchases->isEmpty()) {
            return response()->json([
                'message' => 'Không tìm thấy người dùng nào.',
                'data' => []
            ], 404); // Trả về mã trạng thái 404 nếu không tìm thấy
        }
    
        // Trả về danh sách người dùng mua hàng đã xử lý
        return response()->json($userPurchases);
    }
    
    
    public function getOrdersByUser()
{
    
    $user = Auth::user();
    $orders = Order::with(['orderDetails.product'])
        ->where('status', 1)  // 
        ->where('user_id', $user->id)  
        ->get();


    $orderList = $orders->map(function ($order) {
        $totalOrderPrice = $order->orderDetails->sum(function ($detail) {
            return $detail->quantity * $detail->product->price;
        });
        return [
            'order_id' => $order->id,
            'total_order' => $totalOrderPrice,
            'date' => $order->updated_at,
            'order_details' => $order->orderDetails->map(function ($detail) {
                return [
                    'product_name' => $detail->product->name,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->product->price,
                    'total_price' => $detail->quantity * $detail->product->price,
                ];
            }),
        ];
    });

    return response()->json($orderList);
}


public function deleteOrder(string $id)
{
    
    $order = Order::find($id);
    $order->delete();
        return response()->json([
            'success' => true,
            'message' => 'Xóa thành công',
            'data'=> $order
        ]);
}

public function getAllOrders(Request $request)
{
    // Bước 1: Lấy các tham số tìm kiếm từ yêu cầu
    $username = $request->input('username'); // Lấy username từ yêu cầu
    $email = $request->input('email'); // Lấy email từ yêu cầu

    // Bước 2: Xây dựng truy vấn để lấy danh sách đơn hàng
    $query = Order::with(['orderDetails.product', 'user']) // Lấy thông tin chi tiết của từng đơn hàng và người đặt
                    ->where('status', 1); // Chỉ lấy các đơn hàng đã được xác nhận

    // Bước 3: Nếu có username, thêm điều kiện tìm kiếm theo username
    if ($username) {
        $query->whereHas('user', function ($q) use ($username) {
            $q->where('username', 'LIKE', '%' . $username . '%'); // Tìm kiếm username
        });
    }

    // Bước 4: Nếu có email, thêm điều kiện tìm kiếm theo email
    if ($email) {
        $query->whereHas('user', function ($q) use ($email) {
            $q->where('email', 'LIKE', '%' . $email . '%'); // Tìm kiếm email
        });
    }

    // Bước 5: Thực hiện truy vấn và phân trang
    $orders = $query->paginate(10); // Chia danh sách thành trang, mỗi trang có 10 đơn hàng

    // Bước 6: Khởi tạo mảng để chứa danh sách đơn hàng
    $orderList = [];

    // Duyệt qua từng đơn hàng
    foreach ($orders as $order) {
        // Tính tổng giá trị cho mỗi đơn hàng
        $totalOrderPrice = 0;

        foreach ($order->orderDetails as $detail) {
            // Tính tiền cho từng sản phẩm trong đơn hàng
            $totalOrderPrice += $detail->quantity * $detail->product->price;
        }

        // Thêm thông tin đơn hàng vào mảng
        $orderList[] = [
            'order_id' => $order->id, // ID của đơn hàng
            'total_order' => $totalOrderPrice, // Tổng giá trị của đơn hàng
            'date' => $order->updated_at, // Ngày cập nhật đơn hàng
            'username' => $order->user->username, // Tên người đặt hàng
            'email' => $order->user->email, // Email của người đặt hàng
            'order_details' => [], // Khởi tạo mảng chi tiết đơn hàng
        ];

        // Duyệt qua từng chi tiết đơn hàng
        foreach ($order->orderDetails as $detail) {
            // Thêm thông tin chi tiết sản phẩm vào mảng
            $orderList[count($orderList) - 1]['order_details'][] = [
                'product_name' => $detail->product->name, // Tên sản phẩm
                'quantity' => $detail->quantity, // Số lượng sản phẩm
                'unit_price' => $detail->product->price, // Đơn giá của sản phẩm
                'total_price' => $detail->quantity * $detail->product->price, // Tổng giá trị của sản phẩm trong đơn hàng
            ];
        }
    }

    // Bước 7: Trả về danh sách đơn hàng cùng với thông tin phân trang
    return response()->json([
        'orders' => $orderList, // Danh sách các đơn hàng
        'pagination' => [
            'total' => $orders->total(), // Tổng số đơn hàng
            'per_page' => $orders->perPage(), // Số đơn hàng hiển thị trên mỗi trang
            'current_page' => $orders->currentPage(), // Trang hiện tại
            'last_page' => $orders->lastPage(), // Trang cuối cùng
            'from' => $orders->firstItem(), // Đơn hàng đầu tiên trong trang hiện tại
            'to' => $orders->lastItem(), // Đơn hàng cuối cùng trong trang hiện tại
        ],
    ]);
}


    
}
