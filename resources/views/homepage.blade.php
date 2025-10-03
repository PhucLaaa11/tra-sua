@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <!-- Hero -->
    <section class="text-center p-5 bg-warning">
        <h1 class="fw-bold">Chào mừng đến với Trà Sữa Ngon</h1>
        <p>Đa dạng hương vị – Giá cả hợp lý – Giao hàng tận nơi</p>
        <a href="/products" class="btn btn-dark">Xem Menu</a>
    </section>

    <!-- Sản phẩm nổi bật -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-4">🥤 Sản phẩm nổi bật</h2>
            <div class="row">
                @forelse ($featuredProducts as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        @endif
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ $product->description }}</p>
                            <p class="fw-bold text-danger">{{ number_format($product->price, 0, ',', '.') }} VND</p>
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary">Đặt ngay</a>
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center">Chưa có sản phẩm nổi bật nào.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection