@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container">
    <h1 class="text-center my-4 fw-bold">🧾 My Orders</h1>

    {{-- Display success message --}}
    @if (session('success'))
        <div class="alert alert-success" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @forelse ($orders as $order)
                <div class="card mb-3">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">Order #{{ $order->id }}</h5>
                            <small class="text-muted">Order Date: {{ $order->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <span class="badge 
                            @if($order->status == 'pending') bg-warning text-dark 
                            @elseif($order->status == 'completed') bg-success 
                            @elseif($order->status == 'cancelled') bg-danger 
                            @else bg-secondary 
                            @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            {{-- Order items --}}
                            @foreach ($order->items as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        {{ $item->product_name }} 
                                        <small class="text-muted">x {{ $item->quantity }}</small>
                                    </span>
                                    <span class="fw-bold">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</span>
                                </li>
                            @endforeach
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                <h6 class="mb-0">Total:</h6>
                                <h6 class="mb-0 text-danger fw-bold">{{ number_format($order->total_price, 0, ',', '.') }} đ</h6>
                            </li>
                        </ul>
                        <div class="mt-3">
                            <strong>Shipping Information:</strong>
                            <p class="mb-0">{{ $order->customer_name }} - {{ $order->shipping_phone }}</p>
                            <p class="mb-0">{{ $order->shipping_address }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center p-5">
                    <h4 class="text-muted">You have no orders yet.</h4>
                    <a href="{{ route('homepage') }}" class="btn btn-primary mt-3">
                        <i class="bi bi-cup-straw me-2"></i> Start Shopping
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
