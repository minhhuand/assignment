<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(10);
        return response()->json($products);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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

        // Xử lý file ảnh
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        return response()->json(['message' => 'Product added successfully', 'product' => $product], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->has('name')) {
            $product->name = $request->name;
        }
        if ($request->has('description')) {
            $product->description = $request->description;
        }
        if ($request->has('price')) {
            $product->price = $request->price;
        }

        // Xử lý file ảnh
        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $imagePath = $request->file('image')->store('images', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        // Lấy lại sản phẩm từ cơ sở dữ liệu
        $product = Product::findOrFail($id);

        return response()->json(['message' => 'Cập nhật thành công', 'product' => $product]);
    }


    // Xóa sản phẩm
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Xóa ảnh nếu có
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }


    public function topProducts()
    {
        $products = Product::with('orderDetail')->get();
        return response()->json([
            'data' => $products,
            'success' => true,
        ]);
    }

    public function addProductToOrder(Request $request, string $id)
    {
        $user = $request->user();

        // Tìm đơn hàng của người dùng với status = 0
        $order = Order::where('user_id', $user->id)->where('status', 0)->first();
        $product = Product::find($id);

        // Nếu không có đơn hàng với status = 0
        if (!$order) {
            // Kiểm tra xem có đơn hàng nào với status = 0 hay không
            $existingOrders = Order::where('user_id', $user->id)->where('status', 1)->get();
            $Orders = Order::all();
            // Nếu tất cả các đơn hàng đều có status = 1, tạo đơn hàng mới
            if ($existingOrders->count() > 0 || $existingOrders->count() == 0) {
                $order = new Order();
                $order->user_id = auth()->id();
                $order->total = $product->price;
                $order->status = 0;
                $order->save();

                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $id;
                $orderDetail->quantity = 1;
                $orderDetail->save();
            }
        } else {
            $orderDetail = OrderDetail::where('order_id', $order->id)->where('product_id', $id)->first();

            if ($orderDetail) {

                $orderDetail->quantity += 1;
                $orderDetail->save();
            } else {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $id;
                $orderDetail->quantity = 1;
                $orderDetail->save();
            }
            $order->total =  $order->total +  $product->price;
            $order->save();
        }

        return response()->json([
            'data' => $order,
            'success' => true,
        ]);
    }

    public function getAllOrders()
    {
        // Truy vấn để lấy tất cả các đơn hàng và chi tiết từng đơn hàng
        $orders = Order::with(['orderDetails.product'])->where('status', 1)->get();

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
                        'unit_price' => $detail->product->price,
                        'total_price' => $detail->quantity * $detail->product->price,
                    ];
                }),
            ];
        });

        // Trả về danh sách đơn hàng đã xử lý
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
}
