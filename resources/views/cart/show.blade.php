@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
<div class="container">
    <h1 class="text-center my-4 fw-bold">🛒 Your Shopping Cart</h1>

    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif
    @if (session('warning'))
        <div class="alert alert-warning" role="alert">
            {{ session('warning') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if(count($cartItems) > 0)
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">Image</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Price</th>
                                <th scope="col" style="width: 150px;">Quantity</th>
                                <th scope="col">Total</th>
                                <th scope="col">Remove</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cartItems as $item)
                            <tr>
                                <td>
                                    <img src="{{ $item->attributes->image }}" alt="{{ $item->name }}" 
                                         class="img-fluid rounded" style="width: 70px; height: 70px; object-fit: cover;">
                                </td>
                                <td><h6 class="mb-0">{{ $item->name }}</h6></td>
                                <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                <td>
                                    <!-- Update Quantity Form -->
                                    <form action="{{ route('cart.update') }}" method="POST" class="d-flex">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                                        <input type="number" name="quantity" 
                                               class="form-control form-control-sm text-center" 
                                               value="{{ $item->quantity }}" min="1" max="10">
                                        <button type="submit" class="btn btn-sm btn-outline-primary ms-1" 
                                                title="Update Quantity">
                                            <i class="bi bi-arrow-repeat"></i>
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    <span class="fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</span>
                                </td>
                                <td>
                                    <!-- Remove Item Form -->
                                    <form action="{{ route('cart.remove') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $item->id }}">
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Remove Item">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <hr>

                <div class="row">
                    <!-- Clear Cart Section -->
                    <div class="col-md-6 mb-2 mb-md-0">
                        <form action="{{ route('cart.clear') }}" method="POST" onsubmit="return confirm('Are you sure you want to clear the entire cart?');">
                            @csrf
                            <button type="submit" class="btn btn-danger">
                                <i class="bi bi-trash2-fill me-2"></i> Clear Cart
                            </button>
                        </form>
                    </div>

                    <!-- Total & Checkout Section -->
                    <div class="col-md-6 text-md-end">
                        <h3 class="mb-3">
                            Total: <span class="fw-bolder text-danger">{{ number_format($total, 0, ',', '.') }} đ</span>
                        </h3>
                        
                        <a href="{{ route('checkout.show') }}" class="btn btn-success btn-lg fw-bold">
                            Proceed to Checkout <i class="bi bi-arrow-right-circle-fill ms-2"></i>
                        </a>
                    </div>
                </div>

            @else
                <div class="text-center p-5">
                    <h4 class="text-muted">Your cart is currently empty.</h4>
                    <a href="{{ route('homepage') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-cup-straw me-2"></i> Start Shopping
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection