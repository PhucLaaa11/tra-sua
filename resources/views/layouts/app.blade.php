<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Product Management')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        /* ===== General Layout ===== */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            margin: 0;
        }

        .wrapper {
            display: flex;
            flex: 1;
        }

        /* ===== Sidebar ===== */
        /* --- PART 1: Sticky Menu Configuration --- */
        .sidebar {
            width: 250px;
            height: 100vh;
            position: sticky;
            top: 0;

            /* === ALLOW VERTICAL SCROLL === */
            overflow-y: auto;
            /* Allow VERTICAL scrolling when menu is long */
            overflow-x: hidden;
            /* Completely hide HORIZONTAL scrolling */
            /* ============================ */

            background: #343a40;
            color: white;
            transition: width 0.3s ease;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }

        /* Tip: Style Sidebar scrollbar (if menu is too long) */
        .sidebar::-webkit-scrollbar {
            width: 5px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #555;
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #343a40;
        }

        /* --- PART 2: Collapse Configuration --- */
        .sidebar.collapsed {
            width: 70px;
        }

        .sidebar-header {
            padding: 15px;
            text-align: center;
            font-size: 1.2rem;
            font-weight: bold;
            border-bottom: 1px solid #495057;
            white-space: nowrap;
            overflow: hidden;
        }

        .sidebar.collapsed .sidebar-header span {
            display: none;
        }

        /* ===== Links in sidebar ===== */
        .nav-links {
            flex: 1;
            padding: 10px 0;
        }

        .nav-links a,
        .logout-btn,
        .user-toggle {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #ddd;
            text-decoration: none;
            transition: background 0.3s, color 0.3s;
            white-space: nowrap;
            position: relative;
            border: none;
            background: transparent;
            width: 100%;
            text-align: left;
            cursor: pointer;
        }

        .nav-links a:hover,
        .logout-btn:hover,
        .user-toggle:hover {
            background: #495057;
            color: #fff;
        }

        .nav-links i,
        .logout-btn i,
        .user-toggle i {
            margin-right: 10px;
            font-size: 18px;
            flex-shrink: 0;
        }

        /* Show badge on collapsed sidebar */
        .sidebar .nav-links .badge {
            transition: opacity 0.3s;
        }

        .sidebar.collapsed .nav-links .badge {
            display: none;
            /* Hide text badge when collapsed */
        }


        /* ===== When sidebar is collapsed ===== */
        .sidebar.collapsed .nav-links a span,
        .sidebar.collapsed .logout-btn span,
        .sidebar.collapsed .user-toggle span,
        .sidebar.collapsed .user-toggle i.bi-caret-down {
            display: none;
        }

        .sidebar.collapsed .nav-links a::after,
        .sidebar.collapsed .logout-btn::after,
        .sidebar.collapsed .user-toggle::after,
        .sidebar.collapsed a[data-title]::after {
            content: attr(data-title);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: #000;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            white-space: nowrap;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s, left 0.2s;
            margin-left: 10px;
            font-size: 14px;
            z-index: 1000;
        }

        /* Show badge data-title on hover */
        .sidebar.collapsed .nav-links a:hover::after,
        .sidebar.collapsed .logout-btn:hover::after,
        .sidebar.collapsed .user-toggle:hover::after,
        .sidebar.collapsed a[data-title]:hover::after {
            opacity: 1;
            left: calc(100% + 5px);
        }

        /* ===== Toggle button ===== */
        .toggle-btn {
            margin: 15px auto;
            display: block;
            background: transparent;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #ccc;
            transition: color 0.3s ease;
        }

        .toggle-btn:hover {
            color: #fff;
        }

        /* ===== Main content ===== */
        .main-content {
            flex: 1;
            padding: 20px;
            background: #f8f9fa;
        }

        /* ===== Footer ===== */
        footer {
            background: #f1f1f1;
            text-align: center;
            padding: 10px 0;
            border-top: 1px solid #ddd;
        }

        /* ===== Dropdown when sidebar collapsed ===== */
        .sidebar.collapsed .dropdown-menu {
            position: absolute !important;
            left: 100% !important;
            top: 0 !important;
            margin-left: 5px;
            min-width: 180px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                🍹 <span>Boba Shop</span>
            </div>

            <nav class="nav-links">
                <!-- ========================================= -->
                <!-- 1. PUBLIC LINKS (Visible to everyone)     -->
                <!-- ========================================= -->
                <a href="{{ route('homepage') }}" data-title="Home">
                    <i class="bi bi-house-door"></i> <span>Home</span>
                </a>

                <a href="{{ route('menu.index') }}" data-title="Menu">
                    <i class="bi bi-book"></i> <span>Menu</span>
                </a>


                <!-- ========================================= -->
                <!-- 2. MEMBER AREA (Logged in)                -->
                <!-- ========================================= -->
                @auth

                {{-- 2.1 CUSTOMER LINKS --}}
                @if(Auth::user()->role === 'customer')
                <a href="{{ route('cart.show') }}" data-title="Cart">
                    <i class="bi bi-cart3"></i>
                    <span>
                        Cart
                        @php
                        $cartCount = \Cart::session(Auth::id())->getContent()->count();
                        @endphp
                        @if($cartCount > 0)
                        <span class="badge bg-danger rounded-pill ms-1">{{ $cartCount }}</span>
                        @endif
                    </span>
                </a>
                <a href="{{ route('orders.my') }}" data-title="Order History">
                    <i class="bi bi-clock-history"></i> <span>Order History</span>
                </a>
                @endif

                {{-- 2.2 STAFF & ADMIN LINKS --}}
                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'staff')
                <div class="sidebar-header mt-3 mb-2" style="font-size: 0.8rem; border-bottom: 1px solid #555;">
                    <span>STORE MANAGEMENT</span>
                </div>

                <a href="{{ route('admin.orders.index') }}" data-title="Order Management">
                    <i class="bi bi-clipboard-data"></i> <span>Order Management</span>
                </a>

                {{-- Staff also needs to see this to adjust stock/toggle items --}}
                <a href="{{ route('products.index') }}" data-title="Product Management">
                    <i class="bi bi-list-task"></i> <span>Product Management</span>
                </a>
                @endif

                {{-- 2.3 ADMIN ONLY LINKS (Sensitive functions) --}}
                @if(Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" data-title="Dashboard">
                    <i class="bi bi-speedometer2"></i> <span>Statistics (Dashboard)</span>
                </a>

                <a href="{{ route('products.create') }}" data-title="Add Product">
                    <i class="bi bi-plus-circle"></i> <span>Add Product</span>
                </a>

                <a href="{{ route('admin.categories.index') }}" data-title="Category Management">
                    <i class="bi bi-tags"></i> <span>Category Management</span>
                </a>

                <a href="{{ route('admin.users.index') }}" data-title="User Management">
                    <i class="bi bi-people-fill"></i> <span>User Management</span>
                </a>
                @endif

                @endauth
            </nav>

            <!-- User info / Login -->
            <div class="border-top border-secondary">
                @auth
                <!-- User dropdown -->
                <div class="dropdown nav-links">
                    <button class="user-toggle dropdown-toggle"
                        type="button" data-bs-toggle="dropdown" aria-expanded="false"
                        data-title="{{ Auth::user()->name }}">
                        <i class="bi bi-person-circle"></i> <span>{{ Auth::user()->name }}</span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-dark">
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-light">
                                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
                @else
                <!-- Login link -->
                <nav class="nav-links">
                    <a href="{{ route('login') }}" data-title="Login">
                        <i class="bi bi-box-arrow-in-right"></i> <span>Login</span>
                    </a>
                </nav>
                @endauth
            </div>

            <button class="toggle-btn" id="toggleBtn">
                <i class="bi bi-chevron-double-left"></i>
            </button>
        </aside>

        <!-- Main content -->
        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer>
        <p class="m-0">&copy; {{ date('Y') }} Boba Shop. All rights reserved.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const toggleBtn = document.getElementById('toggleBtn');
        const sidebar = document.getElementById('sidebar');
        const toggleIcon = toggleBtn.querySelector("i");

        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('collapsed');
            toggleIcon.classList.replace("bi-chevron-double-left", "bi-chevron-double-right");
        }

        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
            if (isCollapsed) {
                toggleIcon.classList.replace("bi-chevron-double-left", "bi-chevron-double-right");
            } else {
                toggleIcon.classList.replace("bi-chevron-double-right", "bi-chevron-double-left");
            }
        });
    </script>
</body>

</html>