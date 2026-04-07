<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Calculate total revenue (calculating all orders, filter by status 'completed' if needed)
        $totalRevenue = Order::where('status', '!=', 'cancelled')->sum('total_price');

        // 2. Total number of orders
        $totalOrders = Order::count();

        // 3. Total number of customers (filtering only users with 'customer' role)
        $totalCustomers = User::where('role', 'customer')->count();

        // 4. Total number of products
        $totalProducts = Product::count();

        // 5. Get the 5 most recent orders
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRevenue', 
            'totalOrders', 
            'totalCustomers', 
            'totalProducts',
            'recentOrders'
        ));
    }
}