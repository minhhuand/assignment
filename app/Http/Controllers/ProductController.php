<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::paginate(10);
        return response()->json($products);
    }



    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }


    public function update(Request $request, $id)
    {
     
        // Tìm sản phẩm theo ID
        $product = Product::findOrFail($id);
        
        // Validate request
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $product->fill($request->only(['name', 'description', 'price']));
    
        // Xử lý file ảnh
        if ($request->hasFile('image')) {
            // Xóa ảnh cũ nếu có
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('images', 'public');
            $product->image = $imagePath;
        }
    
        // Lưu thông tin sản phẩm đã cập nhật
        $product->save();
    
        // Trả về phản hồi JSON
        return response()->json(['message' => 'Cập nhật thành công', 'product' => $product]);
    }
    
  



    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }


    public function addProductToOrder(Request $request, string $id)
    {
        // Lấy người dùng hiện tại từ request
        $user = $request->user();
    
        // Lấy số lượng sản phẩm từ request
        $quantity = $request->input('quantity', 1); // Mặc định là 1 nếu không có input 'quantity'
    
        // Tìm đơn hàng của người dùng với status = 0
        $order = Order::where('user_id', $user->id)->where('status', 0)->first();
        $product = Product::find($id);
    
        // Nếu không có đơn hàng với status = 0
        if (!$order) {
            // Kiểm tra xem có đơn hàng nào với status = 0 hay không
            $existingOrders = Order::where('user_id', $user->id)->where('status', 1)->get();
    
            // Nếu không có đơn hàng nào với status = 0, tạo đơn hàng mới
            $order = new Order();
            $order->user_id = auth()->id();
            $order->total = $product->price * $quantity;
            $order->status = 0;
            $order->save();
    
            // Thêm sản phẩm vào chi tiết đơn hàng
            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $id;
            $orderDetail->quantity = $quantity;
            $orderDetail->save();
        } else {
            // Kiểm tra xem sản phẩm đã tồn tại trong đơn hàng hay chưa
            $orderDetail = OrderDetail::where('order_id', $order->id)->where('product_id', $id)->first();
    
            if ($orderDetail) {
                // Cập nhật số lượng nếu sản phẩm đã tồn tại
                $orderDetail->quantity += $quantity;
                $orderDetail->save();
            } else {
                // Thêm sản phẩm mới vào chi tiết đơn hàng nếu chưa tồn tại
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $id;
                $orderDetail->quantity = $quantity;
                $orderDetail->save();
            }
            // Cập nhật tổng tiền đơn hàng
            $order->total += $product->price * $quantity;
            $order->save();
        }
    
        // Trả về phản hồi JSON với thông tin đơn hàng và chi tiết sản phẩm
        return response()->json([
            'data' => $order,
            'success' => true,
        ]);
    }

 
    
    

    public function getTotalSoldProductCounts()
    {
        $soldProducts = OrderDetail::with('product')
            ->selectRaw('product_id, sum(quantity) as total_sold')
            ->whereHas('order', function ($query) {
                $query->where('status', 1);
            })
            ->groupBy('product_id')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Số lượng từng sản phẩm đã bán',
            'data' => $soldProducts,
        ]);
    }

    public function productList(Request $request)
    {
        // Bước 1: Lấy danh sách tất cả sản phẩm cùng với tổng số lượng đã bán của mỗi sản phẩm
        $query = Product::withCount(['orderDetails as total_quantity_sold' => function ($query) {
            $query->select(DB::raw("SUM(quantity)"))->whereHas('order', function ($q) {
                $q->where('status', 1); // Chỉ tính đơn hàng có status = 1
            });
        }]);

        // Bước 2: Nếu có yêu cầu tìm kiếm sản phẩm (theo tên hoặc mô tả)
        if ($request->input('search')) {
            $search = $request->input('search');
            $query = $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        // Bước 3: Lọc sản phẩm bán nhiều nhất
        if ($request->input('most_sold') === 'true') {
            $query = $query->orderBy('total_quantity_sold', 'desc');
        }

        // Bước 4: Lọc sản phẩm bán ít nhất
        if ($request->input('least_sold') === 'true') {
            $query = $query->orderBy('total_quantity_sold', 'asc');
        }

        // Bước 5: Lấy số sản phẩm mỗi trang và số trang hiện tại từ request (nếu không có thì dùng giá trị mặc định)
        $perPage = 10; // Số sản phẩm trên mỗi trang
        $page = $request->input('page', 1); // Trang hiện tại (mặc định là trang 1)

        // Bước 6: Phân trang với phương thức paginate
        $products = $query->paginate($perPage, ['*'], 'page', $page);

        // Bước 7: Chuẩn bị dữ liệu sản phẩm để trả về
        $productList = $products->map(function ($product) {
            return [
                'name' => $product->name, // Tên sản phẩm
                'price' => $product->price, // Giá sản phẩm, định dạng với 2 chữ số thập phân
                'image' => $product->image, // Hình ảnh sản phẩm, sử dụng asset để tạo đường dẫn đầy đủ
                'description' => $product->description, // Mô tả sản phẩm
                'total_quantity_sold' => $product->total_quantity_sold ?? 0, // Số lượng đã bán (nếu chưa có đơn hàng thì là 0)
            ];
        });

        // Bước 8: Trả về danh sách sản phẩm đã chuẩn bị cùng với thông tin phân trang
        return response()->json([
            'data' => $productList,
            'current_page' => $products->currentPage(), // Trang hiện tại
            'last_page' => $products->lastPage(), // Tổng số trang
            'total' => $products->total(), // Tổng số sản phẩm
        ]);
    }
}
