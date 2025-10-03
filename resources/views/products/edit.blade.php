@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Sửa sản phẩm</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number"
                name="price"
                id="price"
                value="{{ old('price', $product->price ?? '') }}"
                step="1"
                min="0"
                class="form-control">

            {{-- Hiển thị lỗi --}}
            @error('price')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>

        <!-- Ảnh -->
        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh sản phẩm</label>
            <input type="file" name="image" class="form-control">
        </div>

        <!-- Sản phẩm nổi bật -->
        <div class="mb-3 form-check">
            <input type="checkbox"
                name="is_featured"
                value="1"
                class="form-check-input"
                {{ $product->is_featured ? 'checked' : '' }}>
            <label class="form-check-label">Đánh dấu là sản phẩm nổi bật</label>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">⬅️ Quay về</a>
    </form>
</div>
@endsection