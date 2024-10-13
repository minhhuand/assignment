<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\OrderDetail;

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
                'sum_quantity' => $order->orderDetails->sum('quantity'),
                'order_details' => $order->orderDetails->map(function ($detail) {
                    return [
                        'id_product' => $detail->product->id,
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

    public function deleteProductCart($order_id, $product_id)
    {
        $orderDetail = OrderDetail::where('order_id', $order_id)
            ->where('product_id', $product_id)
            ->first();
        $orderDetail->delete();
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

        $users = User::with(['orders.orderDetails.product'])->get();
        $orders = Order::with(['orderDetails.product', 'user'])->where('status', 1)->get();
        $userPurchases = $users->map(function ($user) use ($orders) {

            $userOrders = $orders->where('user_id', $user->id);

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
                'total_quantity' => $totalQuantity,
                'total_value' => $totalValue,
            ];
        });

        if ($request->input('exclude_purchased') === 'true') {
            $userPurchases = $userPurchases->filter(function ($user) {
                return $user['total_quantity'] == 0;
            })->values();
        }

        if ($request->input('sort_by_quantity') === 'true') {
            // Sắp xếp danh sách người dùng theo tổng số lượng đã mua giảm dần
            $userPurchases = $userPurchases->sortByDesc('total_quantity')->values();
        }

        if ($request->input('sort_by_value') === 'true') {
            // Sắp xếp danh sách người dùng theo tổng giá trị đơn hàng giảm dần
            $userPurchases = $userPurchases->sortByDesc('total_value')->values();
        }

        $search = $request->input('search');
        if ($search) {
            $userPurchases = $userPurchases->filter(function ($user) use ($search) {
                return str_contains($user['username'], $search) || str_contains($user['email'], $search);
            })->values();
        }


        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $userPurchases->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $userPurchasesPaginated = new LengthAwarePaginator($currentItems, $userPurchases->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);

        if ($userPurchasesPaginated->isEmpty()) {
            return response()->json([
                'message' => 'Không tìm thấy người dùng nào.',
                'data' => []
            ], 404);
        }

        return response()->json($userPurchasesPaginated);
    }



    public function getOrdersByUser()
    {

        $user = Auth::user();
        $orders = Order::with(['orderDetails.product'])
            ->where('status', 1)
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
            'data' => $order
        ]);
    }

    public function getAllOrders(Request $request)
    {

        $username = $request->input('username');
        $email = $request->input('email');

        $query = Order::with(['orderDetails.product', 'user'])
            ->where('status', 1);

        if ($username) {
            $query->whereHas('user', function ($q) use ($username) {
                $q->where('username', 'LIKE', '%' . $username . '%');
            });
        }

        if ($email) {
            $query->whereHas('user', function ($q) use ($email) {
                $q->where('email', 'LIKE', '%' . $email . '%');
            });
        }

        $orders = $query->paginate(10);

        $orderList = [];
        foreach ($orders as $order) {
            $totalOrderPrice = 0;

            foreach ($order->orderDetails as $detail) {
                $totalOrderPrice += $detail->quantity * $detail->product->price;
            }
            $orderList[] = [
                'order_id' => $order->id,
                'total_order' => $totalOrderPrice,
                'date' => $order->updated_at,
                'username' => $order->user->username,
                'email' => $order->user->email,
                'order_details' => [],
            ];


            foreach ($order->orderDetails as $detail) {

                $orderList[count($orderList) - 1]['order_details'][] = [
                    'product_name' => $detail->product->name,
                    'quantity' => $detail->quantity,
                    'unit_price' => $detail->product->price,
                    'total_price' => $detail->quantity * $detail->product->price,
                ];
            }
        }

        return response()->json([
            'orders' => $orderList,
            'pagination' => [
                'total' => $orders->total(),
                'per_page' => $orders->perPage(),
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'from' => $orders->firstItem(),
                'to' => $orders->lastItem(),
            ],
        ]);
    }

    public function updateProductCart(Request $request, $order_id, $product_id)
    {
        $quantity = $request->input('quantity');

        $orderDetail = OrderDetail::where('order_id', $order_id)
            ->where('product_id', $product_id)
            ->first();
        if ($orderDetail) {
            if ($quantity == 0) {

                $orderDetail->delete();
                $remainingOrderDetails = OrderDetail::where('order_id', $order_id)->count();

                if ($remainingOrderDetails == 0) {
                    Order::find($order_id)->delete();
                    return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng và đơn hàng đã bị xóa.'], 200);
                }

                return response()->json(['message' => 'Sản phẩm đã được xóa khỏi giỏ hàng.'], 200);
            } else {
                $orderDetail->quantity = $quantity;
                $orderDetail->save();
                return response()->json(['message' => 'Cập nhật số lượng sản phẩm thành công.'], 200);
            }
        } else {
            return response()->json(['message' => 'Không tìm thấy sản phẩm trong giỏ hàng.'], 404);
        }
    }
    public function upe($id, Request $request)
    {

        echo $request->name;
    }
}
