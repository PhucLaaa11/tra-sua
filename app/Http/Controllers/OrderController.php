<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Display checkout page
     */
    public function showCheckoutForm()
    {
        $userId = Auth::id();
        $cartItems = Cart::session($userId)->getContent();
        $total = Cart::session($userId)->getTotal();

        // If the cart is empty, do not allow checkout
        if ($cartItems->isEmpty()) {
            return redirect()->route('homepage')->with('warning', 'Your cart is empty!');
        }

        // Get user info for auto-fill
        $user = Auth::user();

        return view('checkout.show', compact('cartItems', 'total', 'user'));
    }

    /**
     * Store the order
     */
    public function storeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_address' => 'required|string|max:1000',
        ]);

        $userId = Auth::id();
        $cartItems = Cart::session($userId)->getContent();
        $total = Cart::session($userId)->getTotal();

        if ($cartItems->isEmpty()) {
            return redirect()->route('homepage')->with('warning', 'Your cart is empty!');
        }

        // Use DB Transaction to ensure data safety
        // If one statement fails, everything is rolled back
        try {
            DB::beginTransaction();
            
            // === NEW CODE: Update user information ===
            $user = User::find($userId);
            if ($user) {
                // If the user does not have a phone or address, update it from this order
                $user->phone = $user->phone ?? $request->shipping_phone;
                $user->address = $user->address ?? $request->shipping_address;
                $user->save();
            }

            // 1. Create order
            $order = Order::create([
                'user_id' => $userId,
                'customer_name' => $request->customer_name,
                'shipping_phone' => $request->shipping_phone,
                'shipping_address' => $request->shipping_address,
                'total_price' => $total,
                'status' => 'pending', // Pending status
            ]);

            // 2. Create order items
            foreach ($cartItems as $item) {
                // Get real product from DB to check stock
                $product = \App\Models\Product::find($item->id);

                // Check stock quantity
                if ($product->stock < $item->quantity) {
                    throw new \Exception("Product '{$product->name}' only has {$product->stock} items left. Please reduce the quantity!");
                }

                // Reduce stock
                $product->decrement('stock', $item->quantity);

                // Save order item details
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->id,
                    'product_name' => $item->name,
                    'price' => $item->price,
                    'quantity' => $item->quantity,
                ]);
            }

            // 3. Clear cart
            Cart::session($userId)->clear();

            DB::commit();

            // Redirect to "My Orders" page
            return redirect()->route('orders.my')
                ->with('success', 'Order placed successfully! Thank you.');

        } catch (\Exception $e) {
            DB::rollBack();

            // Redirect back with error message
            return redirect()->route('checkout.show')
                ->with('error', 'An error occurred while placing your order. Please try again.');
        }
    }
    
    // === NEW METHODS START HERE ===
    
    /**
     * Display "My Orders" page
     */
    public function myOrders()
    {
        // Get orders of the logged-in user
        // 'with('items')' loads related items to avoid N+1 queries
        // 'orderBy' sorts with newest orders first
        $orders = Order::where('user_id', Auth::id())
                        ->with('items')
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        // Return 'orders.my' view with orders
        return view('orders.my', ['orders' => $orders]);
    }
}
