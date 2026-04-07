@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Add Product</h1>

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <!-- Select Category -->
        <div class="mb-3">
            <label for="category_id" class="form-label fw-bold">Category</label>
            <select name="category_id" class="form-select">
                <option value="">-- Select Category (Optional) --</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                    </button>
                    @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" name="price" class="form-control" required>

            {{-- Error display --}}
            @error('price')
            <div class="text-danger mt-1">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold">Stock Quantity</label>
            <input type="number" name="stock" class="form-control" value="0" min="0" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <!-- Image -->
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" name="image" class="form-control">
        </div>

        <!-- Featured Product -->
        <div class="mb-3 form-check">
            <input type="checkbox" name="is_featured" value="1" class="form-check-input">
            <label class="form-check-label">Mark as Featured Product</label>
        </div>

        <button type="submit" class="btn btn-success">Save</button>
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
