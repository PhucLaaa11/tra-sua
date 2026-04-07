<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order; // Import model Order

class AdminOrderController extends Controller
{
    /**
     * Display the list of all orders.
     */
    public function index()
    {
        // Get all orders, newest first, paginate 10 orders/page
        $orders = Order::with('user')->orderBy('created_at', 'desc')->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Order status #' . $id . ' updated successfully!');
    }
}