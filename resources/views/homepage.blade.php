@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<!-- Hero Section -->
<section class="text-center p-5 bg-warning text-dark rounded shadow-sm mb-5">
    <h1 class="fw-bold display-5">🥤 Welcome to Boba Shop</h1>
    <p class="lead">Diverse flavors – Reasonable prices – Fast Delivery</p>
    <a href="{{ route('menu.index') }}" class="btn btn-dark btn-lg mt-3">
        View Menu
    </a>
</section>

<!-- Featured Products -->
<section class="py-4">
    <div class="container">
        <h2 class="text-center mb-4 fw-bold">✨ Featured Products</h2>
        <div class="row">
            @forelse ($featuredProducts as $product)
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    @if($product->image)
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             class="card-img-top" alt="{{ $product->name }}" style="height: 220px; object-fit: cover;">
                    </a>
                    @endif
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">
                            <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                                {{ $product->name }}
                            </a>
                        </h5>
                        <p class="text-muted small">{{ $product->description }}</p>
                        <p class="fw-bold text-danger fs-5">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                        
                        <!-- Add to Cart Form -->
                        <form action="{{ route('cart.add') }}" method="POST" class="d-inline">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-success">
                                🛒 Add to Cart
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <p class="text-center text-muted">No featured products available.</p>
            @endforelse
        </div>
    </div>
</section>
@endsection