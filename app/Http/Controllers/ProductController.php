<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

class ProductController extends Controller
{
    public function homepage()
    {
        // Get 3 featured products
        $featuredProducts = Product::where('is_featured', true)->take(3)->get();

        return view('homepage', compact('featuredProducts'));
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Initialize query
        $query = Product::query();

        // 1. Search Logic
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        // 2. Sorting Logic (for future expansion)
        // Default sort by newest
        $query->orderBy('created_at', 'desc');

        // 3. Pagination (8 items per page)
        $products = $query->paginate(8);

        // Append search parameters to pagination links (important!)
        $products->appends($request->all());

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'stock'       => 'required|integer|min:0', 
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048', // accepts jpg, png, webp...
            'is_featured' => 'sometimes|boolean'
        ]);

        // Handle image upload if present
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        // Default is_featured to false if not present
        $validated['is_featured'] = $request->boolean('is_featured', false);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::findOrFail($id);
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // Get categories to display in edit form
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'stock'       => 'required|integer|min:0', 
            'price'       => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|max:2048',
            'is_featured' => 'sometimes|boolean'
        ]);

        // Handle image upload and override
        // If a new image is uploaded
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($product->image && Storage::disk('public')->exists($product->image)) {
                Storage::disk('public')->delete($product->image);
            }

            // Save new image
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // Default false if not present
        $validated['is_featured'] = $request->boolean('is_featured', false);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        if ($product->image && Storage::disk('public')->exists($product->image)) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully!');
    }

    /**
     * Menu Page for Customers (With Search & Category Filter)
     */
    public function menu(Request $request)
    {
        // Initialize query and filter only active items (is_active = true)
        $query = Product::query()->where('is_active', true);

        // 1. Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%");
        }

        // 2. Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        // Get product list (8 items/page)
        $products = $query->orderBy('is_featured', 'desc') // Featured items first
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->withQueryString(); // Keep query parameters in URL when paginating

        // Get categories list for sidebar
        // withCount('products') to count how many items in each category
        $categories = Category::withCount('products')->get();

        return view('menu', compact('products', 'categories'));
    }

    /**
     * Quick Update Function (For Staff & Admin)
     */
    public function quickUpdate(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Update stock (if input exists)
        if ($request->has('stock')) {
            $product->stock = $request->stock;
        }

        // Update Toggle On/Off status (if sent)
        if ($request->has('is_active')) {
            $product->is_active = $request->boolean('is_active');
        }

        $product->save();

        return redirect()->back()->with('success', 'Product status updated successfully!');
    }
}