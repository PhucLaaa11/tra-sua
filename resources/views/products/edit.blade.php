@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Product</h1>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
        </div>

        <!-- Select Category (Edit) -->
        <div class="mb-3">
            <label for="category_id" class="form-label fw-bold">Category</label>
            <select name="category_id" class="form-select">
                <option value="">-- Select Category --</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}"
                    {{ (old('category_id') ?? $product->category_id) == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number"
                name="price"
                id="price"
                value="{{ old('price', $product->price ?? '') }}"
                step="1"
                min="0"
                class="form-control">

            {{-- Display errors --}}
            @error('price')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Stock Quantity</label>
            <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" min="0" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control">{{ $product->description }}</textarea>
        </div>

        <!-- Image -->
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <!-- Featured Product -->
        <div class="mb-3 form-check">
            <input type="checkbox"
                name="is_featured"
                value="1"
                class="form-check-input"
                {{ $product->is_featured ? 'checked' : '' }}>
            <label class="form-check-label">Mark as Featured Product</label>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ url()->previous() }}" class="btn btn-secondary">⬅️ Back</a>
    </form>
</div>
@endsection