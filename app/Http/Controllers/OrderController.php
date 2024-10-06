<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

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
                        'unit_price' => $detail->product->price,
                        'total_price' => $detail->quantity * $detail->product->price,
                    ];
                }),
            ];
        });

        // Trả về danh sách đơn hàng đã xử lý
        return response()->json($orderList);
    }
}
