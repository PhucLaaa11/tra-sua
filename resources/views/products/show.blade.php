@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <!-- PART 1: PRODUCT DETAILS -->
    <div class="card shadow-sm border-0 mb-5">
        <div class="row g-0">
            <!-- Product Image -->
            <div class="col-md-5">
                <img src="{{ $product->image ? asset('storage/' . $product->image) : 'https://placehold.co/600x600?text=No+Image' }}" 
                     class="img-fluid rounded-start w-100 h-100" 
                     style="object-fit: cover; min-height: 400px;" 
                     alt="{{ $product->name }}">
            </div>
            
            <!-- Product Info & Order Form -->
            <div class="col-md-7">
                <div class="card-body p-4 p-md-5">
                    <h1 class="fw-bold mb-3">{{ $product->name }}</h1>
                    
                    <!-- Average Rating -->
                    <div class="mb-3 d-flex align-items-center">
                        <span class="text-warning fs-5 me-2">
                            <i class="bi bi-star-fill"></i>
                            <strong>{{ number_format($product->avgRating(), 1) }}</strong>/5
                        </span>
                        <span class="text-muted small">({{ $product->reviews->count() }} reviews)</span>
                    </div>

                    <h2 class="text-danger fw-bold mb-4">{{ number_format($product->price, 0, ',', '.') }} đ</h2>
                    
                    <p class="card-text text-secondary mb-5" style="line-height: 1.8;">
                        {{ $product->description ?? 'Description updating...' }}
                    </p>

                    <!-- SMART BACK BUTTON -->
                    <div class="d-flex flex-column gap-3">
                        <div>
                            @if(auth()->check() && auth()->user()->role === 'admin')
                                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                    ⬅ Back to Management
                                </a>
                            @else
                                <a href="{{ route('menu.index') }}" class="btn btn-secondary">
                                    ⬅ Back to Menu
                                </a>
                            @endif
                        </div>

                        <!-- Add to Cart & Stock Logic -->
                        @if($product->stock > 0)
                            <form action="{{ route('cart.add') }}" method="POST" class="d-flex align-items-center gap-3">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                
                                <div class="input-group" style="width: 140px;">
                                    <span class="input-group-text bg-white">Qty</span>
                                    <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control text-center fw-bold">
                                </div>

                                <button type="submit" class="btn btn-primary btn-lg px-5">
                                    <i class="bi bi-cart-plus me-2"></i> Add to Cart
                                </button>
                            </form>
                            <div class="mt-2 text-success"><i class="bi bi-check-circle"></i> {{ $product->stock }} items left</div>
                        @else
                            <!-- Out of Stock Button -->
                            <button class="btn btn-secondary btn-lg px-5" disabled>
                                Temporarily Out of Stock
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PART 2: REVIEWS & COMMENTS -->
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h3 class="fw-bold mb-4 border-bottom pb-2">
                <i class="bi bi-chat-quote-fill text-primary me-2"></i> Customer Reviews
            </h3>

            <!-- Review Form (Only show if logged in) -->
            @auth
            <div class="card mb-4 bg-light border-0">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3">Write your review</h5>
                    <form action="{{ route('reviews.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Your Rating:</label>
                            <select name="rating" class="form-select w-auto">
                                <option value="5">⭐⭐⭐⭐⭐ (Excellent)</option>
                                <option value="4">⭐⭐⭐⭐ (Good)</option>
                                <option value="3">⭐⭐⭐ (Average)</option>
                                <option value="2">⭐⭐ (Poor)</option>
                                <option value="1">⭐ (Very Poor)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Comment:</label>
                            <textarea name="comment" class="form-control" rows="3" placeholder="Share your thoughts about this product..."></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Submit Review</button>
                    </form>
                </div>
            </div>
            @else
            <div class="alert alert-info text-center">
                Please <a href="{{ route('login') }}" class="fw-bold">login</a> to write a review.
            </div>
            @endauth

            <!-- List of existing reviews -->
            <div class="review-list">
                @forelse($product->reviews as $review)
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center me-2" 
                                     style="width: 40px; height: 40px;">
                                    {{ substr($review->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">{{ $review->user->name }}</h6>
                                    <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                            <!-- Display Gold Stars -->
                            <div class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $review->rating)
                                        <i class="bi bi-star-fill"></i>
                                    @else
                                        <i class="bi bi-star"></i>
                                    @endif
                                @endfor
                            </div>
                        </div>
                        <p class="card-text bg-light p-3 rounded">
                            {{ $review->comment ?? 'Customer did not leave a comment.' }}
                        </p>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-chat-square-dots fs-1 mb-2"></i>
                    <p>No reviews yet. Be the first to review!</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection