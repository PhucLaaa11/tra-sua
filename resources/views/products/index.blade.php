@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Danh sách sản phẩm</h1>

    <div class="mb-3 d-flex justify-content-between">
        <a href="{{ route('products.create') }}" class="btn btn-success">+ Thêm sản phẩm</a>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">⬅️ Quay về</a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>STT</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Mô tả</th>
                <th>Hình ảnh</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $product)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $product->name }}</td>
                <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                <td>{{ $product->description }}</td>
                <td>
                    @if (!empty($product->image))
                    <img src="{{ asset('storage/' . $product->image) }}"
                        alt="{{ $product->name }}"
                        style="width: 80px; height: auto; border-radius: 5px; object-fit: cover;">
                    @else
                    <span class="text-muted">Chưa có ảnh</span>
                    @endif
                </td>
                <td>
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">Sửa</a>

                    <form action="{{ route('products.destroy', $product->id) }}"
                        method="POST"
                        style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Bạn có chắc muốn xóa sản phẩm này không?')">
                            Xóa
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
