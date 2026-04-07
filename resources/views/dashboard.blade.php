@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Hello, {{ Auth::user()->name }} 👋</h4>
        </div>
        <div class="card-body">
            <p>Welcome to the Milk Tea Management System.</p>
            <p><strong>Your role:</strong> {{ ucfirst(Auth::user()->role) }}</p>

            @if (Auth::user()->role === 'admin')
                <div class="alert alert-info mt-3">
                    This page is for <strong>Admin</strong>. You can manage staff, customers, and products.
                </div>
            @elseif (Auth::user()->role === 'staff')
                <div class="alert alert-success mt-3">
                    This page is for <strong>Staff</strong>. You can manage orders and support customers.
                </div>
            @else
                <div class="alert alert-warning mt-3">
                    This page is for <strong>Customer</strong>. Thank you for supporting our Delicious Milk Tea Shop 🍹
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
