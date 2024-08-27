<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['customer', 'items.product'])
            ->get();

        $orderData = $orders->map(function($order) {
            $totalAmount = $order->items->sum(function($item) {
                return $item->price * $item->quantity;
            });

            $itemsCount = $order->items->count();

            $lastAddedToCart = CartItem::where('order_id', $order->id)
                ->orderByDesc('created_at')
                ->value('created_at');

            $completedOrderExists = $order->status === 'completed';

            return [
                'order_id' => $order->id,
                'customer_name' => $order->customer->name,
                'total_amount' => $totalAmount,
                'items_count' => $itemsCount,
                'last_added_to_cart' => $lastAddedToCart,
                'completed_order_exists' => $completedOrderExists,
                'created_at' => $order->created_at,
            ];
        });

        // Sıralama SQL sorgusu ile yapılır
        $orderData = $orderData->sortByDesc(function($order) {
            return Order::where('id', $order['order_id'])
                ->where('status', 'completed')
                ->value('completed_at');
        });

        return view('orders.index', ['orders' => $orderData->values()]);
    }
}
