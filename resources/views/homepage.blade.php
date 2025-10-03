@extends('layouts.app')

@section('title', 'Trang chủ')

@section('content')
    <!-- Hero -->
    <section class="text-center py-16 bg-gradient-to-r from-pink-500 to-yellow-400 text-white rounded-xl shadow-lg">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">🍹 Chào mừng đến với Trà Sữa Ngon</h1>
        <p class="mb-6 text-lg">Đa dạng hương vị – Giá cả hợp lý – Giao hàng tận nơi</p>
        <a href="{{ route('products.index') }}" 
           class="bg-white text-pink-600 px-6 py-3 rounded-lg font-semibold shadow hover:bg-gray-100 transition">
            Xem Menu
        </a>
    </section>

    <!-- Sản phẩm nổi bật -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-center mb-8">🥤 Sản phẩm nổi bật</h2>
            
            @if($featuredProducts->count())
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach ($featuredProducts as $product)
                        <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="{{ $product->name }}" 
                                     class="w-full h-48 object-cover">
                            @endif
                            <div class="p-5 text-center">
                                <h3 class="font-semibold text-lg">{{ $product->name }}</h3>
                                <p class="text-gray-600 text-sm mt-2">{{ Str::limit($product->description, 80) }}</p>
                                <p class="text-pink-600 font-bold text-lg mt-3">
                                    {{ number_format($product->price, 0, ',', '.') }} VND
                                </p>
                                <a href="{{ route('products.show', $product->id) }}" 
                                   class="mt-4 inline-block bg-pink-500 text-white px-5 py-2 rounded-lg shadow hover:bg-pink-600 transition">
                                    Đặt ngay
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-center text-gray-600">Chưa có sản phẩm nổi bật nào.</p>
            @endif
        </div>
    </section>
@endsection
