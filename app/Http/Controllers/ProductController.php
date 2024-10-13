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
        $products = Product::orderBy('id', 'desc')->paginate(10);
        return response()->json($products);
    }



    public function store(Request $request)
    {
        // $request->validate([
        //     'name' => 'required|string|max:255',
        //     'description' => 'nullable|string',
        //     'price' => 'required|numeric',
        //     'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ]);

        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        $productId = $product->id;

        return response()->json(['message' => 'Product added successfully', 'product_id' => $productId, 'product' => $product], 201);
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);


        $product = Product::find($id);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($request->filled('name')) {
            $product->name = $request->name;
        }

        if ($request->filled('description')) {
            $product->description = $request->description;
        }

        if ($request->filled('price')) {
            $product->price = $request->price;
        }

        if ($request->hasFile('image')) {

            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }


            $imagePath = $request->file('image')->store('images', 'public');
            $product->image = $imagePath;
        }

        $product->save();

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 200);
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
        $user = $request->user();

        $quantity = $request->input('quantity', 1);

        $order = Order::where('user_id', $user->id)->where('status', 0)->first();
        $product = Product::find($id);

        if (!$order) {
            $existingOrders = Order::where('user_id', $user->id)->where('status', 1)->get();
            $order = new Order();
            $order->user_id = auth()->id();
            $order->total = $product->price * $quantity;
            $order->status = 0;
            $order->save();

            $orderDetail = new OrderDetail();
            $orderDetail->order_id = $order->id;
            $orderDetail->product_id = $id;
            $orderDetail->quantity = $quantity;
            $orderDetail->save();
        } else {
            $orderDetail = OrderDetail::where('order_id', $order->id)->where('product_id', $id)->first();

            if ($orderDetail) {
                $orderDetail->quantity += $quantity;
                $orderDetail->save();
            } else {
                $orderDetail = new OrderDetail();
                $orderDetail->order_id = $order->id;
                $orderDetail->product_id = $id;
                $orderDetail->quantity = $quantity;
                $orderDetail->save();
            }
            $order->total += $product->price * $quantity;
            $order->save();
        }

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

        $query = Product::withCount(['orderDetails as total_quantity_sold' => function ($query) {
            $query->select(DB::raw("SUM(quantity)"))->whereHas('order', function ($q) {
                $q->where('status', 1); // Chỉ tính đơn hàng có status = 1
            });
        }]);

        if ($request->input('search')) {
            $search = $request->input('search');
            $query = $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        if ($request->input('most_sold') === 'true') {
            $query = $query->orderBy('total_quantity_sold', 'desc');
        }

        if ($request->input('least_sold') === 'true') {
            $query = $query->orderBy('total_quantity_sold', 'asc');
        }

        $perPage = 10;
        $page = $request->input('page', 1);
        $products = $query->paginate($perPage, ['*'], 'page', $page);

        $productList = $products->map(function ($product) {
            return [
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'description' => $product->description,
                'total_quantity_sold' => $product->total_quantity_sold ?? 0,
            ];
        });

        return response()->json([
            'data' => $productList,
            'current_page' => $products->currentPage(),
            'last_page' => $products->lastPage(),
            'total' => $products->total(),
        ]);
    }
}
