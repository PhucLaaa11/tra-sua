@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-lg border-0 rounded-4">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         class="card-img-top rounded-top-4" 
                         alt="{{ $product->name }}" 
                         style="max-height: 350px; object-fit: cover;">
                @else
                    <img src="https://via.placeholder.com/600x350?text=No+Image" 
                         class="card-img-top rounded-top-4" 
                         alt="No Image">
                @endif

                <div class="card-body">
                    <h2 class="card-title fw-bold">{{ $product->name }}</h2>
                    <p class="text-muted">{{ $product->description }}</p>

                    <h4 class="text-danger fw-bold mb-4">
                        {{ number_format($product->price, 0, ',', '.') }} VND
                    </h4>

                    <div class="d-flex gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            ⬅ Quay lại
                        </a>
                        <a href="#" class="btn btn-primary">
                            🛒 Thêm vào giỏ hàng
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
