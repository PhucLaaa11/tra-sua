@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="fw-bold mb-4">📂 Category Management</h1>
    
    <div class="card shadow-sm">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
            <h5 class="m-0 font-weight-bold text-primary">Category List</h5>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-success btn-sm">
                <i class="bi bi-plus-lg"></i> Add New
            </a>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Category Name</th>
                        <th>Description</th>
                        <th>Product Count</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $cat)
                    <tr>
                        <td>{{ $cat->id }}</td>
                        <td class="fw-bold">{{ $cat->name }}</td>
                        <td>{{ $cat->description }}</td>
                        <td><span class="badge bg-info">{{ $cat->products_count }} items</span></td>
                        <td>
                            <a href="{{ route('admin.categories.edit', $cat->id) }}" class="btn btn-primary btn-sm">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $cat->id) }}" method="POST" class="d-inline">
                                @csrf @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this category?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection