@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Thêm sản phẩm</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <!-- <input type="number" name="price" step="1000" class="form-control" required> -->
            <input type="number" name="price" class="form-control" required>

            {{-- Hiển thị lỗi --}}
            @error('price')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <!-- Ảnh -->
        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh sản phẩm</label>
            <input type="file" name="image" class="form-control">
        </div>

        <!-- Sản phẩm nổi bật -->
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_featured" value="1" class="form-check-input">
            <label class="form-check-label">Đánh dấu là sản phẩm nổi bật</label>
        </div>

        <button type="submit" class="btn btn-success">Lưu</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Huỷ</a>
    </form>
</div>
@endsection