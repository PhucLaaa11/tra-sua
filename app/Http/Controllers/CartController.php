<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product; // Import Product model
use Illuminate\Support\Facades\Auth; // Import Auth
use Darryldecode\Cart\Facades\CartFacade as Cart;

class CartController extends Controller
{
    /**
     * Display the cart page.
     */
    public function show()
    {
        // Get the ID of the logged-in user
        $userId = Auth::id(); 
        
        // Get the cart content OF THAT USER
        // Now we use Cart:: instead of \Cart::
        $cartItems = Cart::session($userId)->getContent();
        $total = Cart::session($userId)->getTotal();

        return view('cart.show', compact('cartItems', 'total'));
    }

    /**
     * Add a product to the cart.
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1'
        ]);

        $product = Product::findOrFail($request->product_id);
        $userId = Auth::id(); // Get the user ID
        
        // Add to the cart OF THAT USER
        Cart::session($userId)->add([
            'id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => (int) $request->quantity,
            'attributes' => [
                'image' => $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/100'
            ]
        ]);

        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    /**
     * Update product quantity.
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1'
        ]);

        $userId = Auth::id();
        
        Cart::session($userId)->update($request->product_id, [
            'quantity' => [
                'relative' => false, // Set the exact quantity
                'value' => (int) $request->quantity
            ],
        ]);

        return redirect()->route('cart.show')->with('success', 'Cart updated successfully!');
    }

    /**
     * Remove a product from the cart.
     */
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();
        Cart::session($userId)->remove($request->product_id);

        return redirect()->route('cart.show')->with('success', 'Product removed from cart successfully!');
    }
    
    /**
     * Clear the entire cart.
     */
    public function clear()
    {
        $userId = Auth::id();
        Cart::session($userId)->clear();

        return redirect()->route('cart.show')->with('success', 'Cart cleared successfully!');
    }
}
