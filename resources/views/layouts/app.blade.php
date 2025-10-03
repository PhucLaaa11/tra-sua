<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sản phẩm</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="flex flex-col min-h-screen bg-gradient-to-br from-orange-50 to-yellow-50">

    <div class="flex flex-1">
        <!-- Sidebar -->
        <aside id="sidebar" 
            class="w-64 bg-gradient-to-b from-orange-500 to-amber-600 text-white flex flex-col shadow-lg transition-all duration-300 ease-in-out rounded-r-2xl">
            
            <!-- Header -->
            <div class="p-4 text-center font-bold border-b border-orange-400 flex items-center justify-center">
                🧋 <span class="ml-2 sidebar-text">Trà Sữa Ngon</span>
            </div>

            <!-- Links -->
            <nav class="flex-1 mt-4 space-y-2">
                <a href="{{ route('homepage') }}" 
                   class="flex items-center px-4 py-2 mx-2 rounded-lg hover:bg-orange-400 hover:scale-[1.02] transition">
                    <i class="ph ph-house text-lg"></i>
                    <span class="ml-2 sidebar-text">Trang chủ</span>
                </a>
                <a href="{{ route('products.index') }}" 
                   class="flex items-center px-4 py-2 mx-2 rounded-lg hover:bg-orange-400 hover:scale-[1.02] transition">
                    <i class="ph ph-list text-lg"></i>
                    <span class="ml-2 sidebar-text">Danh sách sản phẩm</span>
                </a>
                <a href="{{ route('products.create') }}" 
                   class="flex items-center px-4 py-2 mx-2 rounded-lg hover:bg-orange-400 hover:scale-[1.02] transition">
                    <i class="ph ph-plus-circle text-lg"></i>
                    <span class="ml-2 sidebar-text">Thêm sản phẩm</span>
                </a>
            </nav>

            <!-- Logout -->
            <div class="border-t border-orange-400 p-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                        class="flex items-center w-full px-4 py-2 mx-2 rounded-lg bg-red-500/80 hover:bg-red-600 hover:scale-[1.02] transition">
                        <i class="ph ph-sign-out text-lg"></i>
                        <span class="ml-2 sidebar-text">Đăng xuất</span>
                    </button>
                </form>
            </div>

            <!-- Toggle button -->
            <button id="toggleBtn" 
                class="mx-auto my-3 text-orange-200 hover:text-white transition">
                <i class="ph ph-caret-double-left text-xl"></i>
            </button>
        </aside>

        <!-- Main content -->
        <main class="flex-1 p-6">
            @yield('content')
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-orange-100 text-center py-3 border-t border-orange-200">
        <p class="text-sm text-orange-700">&copy; {{ date('Y') }} Quản lý Trà Sữa. All rights reserved.</p>
    </footer>

    <!-- Script toggle sidebar -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('toggleBtn');
        const toggleIcon = toggleBtn.querySelector("i");
        const texts = document.querySelectorAll('.sidebar-text');

        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            sidebar.classList.add('w-20');
            sidebar.classList.remove('w-64');
            texts.forEach(t => t.style.display = 'none');
            toggleIcon.classList.replace("ph-caret-double-left", "ph-caret-double-right");
        }

        toggleBtn.addEventListener('click', () => {
            if (sidebar.classList.contains('w-64')) {
                sidebar.classList.remove('w-64');
                sidebar.classList.add('w-20');
                texts.forEach(t => t.style.display = 'none');
                localStorage.setItem('sidebar-collapsed', 'true');
                toggleIcon.classList.replace("ph-caret-double-left", "ph-caret-double-right");
            } else {
                sidebar.classList.add('w-64');
                sidebar.classList.remove('w-20');
                texts.forEach(t => t.style.display = 'inline');
                localStorage.setItem('sidebar-collapsed', 'false');
                toggleIcon.classList.replace("ph-caret-double-right", "ph-caret-double-left");
            }
        });
    </script>
</body>
</html>
