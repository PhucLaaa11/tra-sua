@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="text-center fw-bold mb-4">Product Management</h1>

    <!-- 1. TOOLBAR: SEARCH & ADD -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center">
                <!-- Search Form -->
                <div class="col-md-8">
                    <form action="{{ route('products.index') }}" method="GET" class="d-flex gap-2">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search product name..." 
                               value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary text-nowrap">
                            <i class="bi bi-search"></i> Search
                        </button>
                        @if(request('search'))
                            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary text-nowrap">
                                <i class="bi bi-x-lg"></i> Clear
                            </a>
                        @endif
                    </form>
                </div>
                
                <!-- Add Button (Admin Only) -->
                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('products.create') }}" class="btn btn-success">
                        <i class="bi bi-plus-circle"></i> Add Product
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- 2. PRODUCT LIST TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead class="table-light text-center">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th style="width: 80px;">Image</th>
                            <th>Product Name</th>
                            <th style="width: 150px;">Price</th>
                            <th style="width: 250px;">Quick Manage</th>
                            <th style="width: 120px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $index => $product)
                        <tr class="{{ !$product->is_active ? 'table-secondary' : '' }}">
                            <td class="text-center">{{ $products->firstItem() + $index }}</td>
                            
                            <td class="text-center">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/70' }}"
                                     class="rounded border" style="width: 60px; height: 60px; object-fit: cover;">
                            </td>
                            
                            <td>
                                <div class="fw-bold {{ !$product->is_active ? 'text-decoration-line-through text-muted' : 'text-primary' }}">
                                    {{ $product->name }}
                                </div>
                                <small class="text-muted d-block">
                                    {{ $product->category->name ?? 'Uncategorized' }}
                                </small>
                                
                                {{-- STATUS BADGE --}}
                                @if(!$product->is_active)
                                    <span class="badge bg-danger mt-1">Inactive</span>
                                @else
                                    <span class="badge bg-success mt-1">Active</span>
                                @endif
                            </td>
                            
                            <td class="text-danger fw-bold text-end">
                                {{ number_format($product->price, 0, ',', '.') }} đ
                            </td>

                            {{-- QUICK MANAGE COLUMN --}}
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    
                                    {{-- Form 1: Save Stock --}}
                                    <form action="{{ route('products.quick_update', $product->id) }}" method="POST" class="d-flex">
                                        @csrf @method('PUT')
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white text-muted">Stock</span>
                                            <input type="number" name="stock" value="{{ $product->stock }}" 
                                                   class="form-control text-center fw-bold" 
                                                   style="width: 60px;" min="0">
                                            <button type="submit" class="btn btn-primary" title="Save Stock">
                                                <i class="bi bi-save"></i>
                                            </button>
                                        </div>
                                    </form>

                                    {{-- Form 2: Toggle Active --}}
                                    <form action="{{ route('products.quick_update', $product->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="is_active" value="{{ $product->is_active ? 0 : 1 }}">
                                        
                                        @if($product->is_active)
                                            <button type="submit" class="btn btn-outline-success btn-sm border-0" title="Active (Click to Deactivate)">
                                                <i class="bi bi-toggle-on fs-3"></i>
                                            </button>
                                        @else
                                            <button type="submit" class="btn btn-outline-secondary btn-sm border-0" title="Inactive (Click to Activate)">
                                                <i class="bi bi-toggle-off fs-3"></i>
                                            </button>
                                        @endif
                                    </form>
                                </div>
                            </td>
                            
                            <td class="text-center">
                                @if(Auth::user()->role === 'admin')
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                            <i class="bi bi-pencil-square"></i>
                                        </a>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" 
                                                    onclick="return confirm('Are you sure you want to delete this product?')" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted small">View Only</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">No products found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection