@extends('layouts.app')

@section('title', 'Menu')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- LEFT COLUMN: CATEGORY -->
        <div class="col-lg-3 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold text-uppercase">
                    <i class="bi bi-list-ul me-2"></i> Categories
                </div>
                <div class="list-group list-group-flush">
                    <!-- "All" button -->
                    <a href="{{ route('menu.index') }}"
                        class="list-group-item list-group-item-action {{ !request('category') ? 'active fw-bold' : '' }}">
                        All Items
                    </a>

                    <!-- Dynamic categories -->
                    @foreach($categories as $cat)
                    <a href="{{ route('menu.index', ['category' => $cat->id]) }}"
                        class="list-group-item list-group-item-action d-flex justify-content-between align-items-center {{ request('category') == $cat->id ? 'active fw-bold' : '' }}">
                        {{ $cat->name }}
                        <span class="badge bg-secondary rounded-pill">{{ $cat->products_count }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            <!-- Small search bar in sidebar -->
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body">
                    <form action="{{ route('menu.index') }}" method="GET">
                        @if(request('category'))
                        <input type="hidden" name="category" value="{{ request('category') }}">
                        @endif
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search item..." value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- RIGHT COLUMN: ITEM LIST -->
        <div class="col-lg-9">
            <h2 class="fw-bold mb-4">
                @if(request('category'))
                @php $catName = $categories->firstWhere('id', request('category'))->name ?? 'Category'; @endphp
                Menu: <span class="text-primary">{{ $catName }}</span>
                @else
                Full Store Menu
                @endif
            </h2>

            @if($products->count() > 0)
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($products as $product)
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 transition-hover">
                        <div class="position-relative">
                            <!-- Click the image to view details & comments -->
                            <a href="{{ route('products.show', $product->id) }}">
                                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/300x200?text=No+Image' }}"
                                    class="card-img-top" alt="{{ $product->name }}"
                                    style="height: 200px; object-fit: cover;">
                            </a>
                            @if($product->is_featured)
                            <span class="position-absolute top-0 end-0 bg-danger text-white px-2 py-1 m-2 rounded small fw-bold">
                                Hot
                            </span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold text-dark">{{ $product->name }}</h5>
                            <p class="card-text text-muted small flex-grow-1">{{ Str::limit($product->description, 60) }}</p>
                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <span class="fs-5 fw-bold text-primary">{{ number_format($product->price, 0, ',', '.') }} đ</span>

                                <!-- Add to cart form -->
                                @if($product->stock > 0)
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit" class="btn btn-outline-success rounded-circle p-2" title="Add to cart">
                                        <i class="bi bi-cart-plus fs-5"></i>
                                    </button>
                                </form>
                                @else
                                <button class="btn btn-secondary rounded-circle p-2" disabled title="Out of stock">
                                    <i class="bi bi-x-lg fs-5"></i>
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-5 d-flex justify-content-center">
                {{ $products->links() }}
            </div>
            @else
            <div class="alert alert-warning text-center">
                No matching items found!
            </div>
            @endif
        </div>
    </div>
</div>

<style>
    .transition-hover {
        transition: transform 0.2s;
    }

    .transition-hover:hover {
        transform: translateY(-5px);
    }
</style>
@endsection
