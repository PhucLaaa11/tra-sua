@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <h1 class="text-center my-4 fw-bold">Payment Confirmation</h1>

    {{-- Display error message if exists --}}
    @if (session('error'))
    <div class="alert alert-danger" role="alert">
        {{ session('error') }}
    </div>
    @endif

    <div class="row">
        <!-- Shipping Information Column -->
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Shipping Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('order.store') }}" method="POST" id="checkout-form">
                        @csrf

                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Recipient's Full Name</label>
                            {{-- Pre-fill user name but allow editing --}}
                            <input type="text" class="form-control" id="customer_name" name="customer_name"
                                value="{{ old('customer_name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="shipping_phone" name="shipping_phone"
                                value="{{ old('shipping_phone', auth()->user()->phone) }}" placeholder="09xxxxxxxx" required>
                        </div>

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <textarea class="form-control" id="shipping_address" name="shipping_address" rows="3"
                                required>{{ old('shipping_address', auth()->user()->address) }}</textarea>
                        </div>

                        <p class="text-muted small">
                            * Please double-check your information before confirming.
                        </p>
                    </form>
                </div>
            </div>
        </div>

        <!-- Order Summary Column -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0"><i class="bi bi-cart-check-fill me-2"></i>Order Summary</h5>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach ($cartItems as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{ $item->name }}</h6>
                                <small class="text-muted">Quantity: {{ $item->quantity }}</small>
                            </div>
                            <span class="text-dark fw-bold">
                                {{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ
                            </span>
                        </li>
                        @endforeach

                        <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                            <h5 class="mb-0">Total:</h5>
                            <h5 class="mb-0 text-danger fw-bolder">{{ number_format($total, 0, ',', '.') }} đ</h5>
                        </li>
                    </ul>
                </div>

                <div class="card-footer p-3">
                    {{-- This button submits the form on the left --}}
                    <button type="submit" form="checkout-form" class="btn btn-success btn-lg w-100 fw-bold">
                        <i class="bi bi-check-circle-fill me-2"></i>
                        Confirm Order
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
