<x-guest-layout>
    <div class="max-w-md mx-auto mt-12 bg-white p-8 shadow-lg rounded-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Quên mật khẩu</h2>

        <p class="mb-4 text-sm text-gray-600">
            Nhập email của bạn, chúng tôi sẽ gửi link đặt lại mật khẩu.
        </p>

        @if (session('status'))
            <div class="mb-4 text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
                @error('email')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit -->
            <button type="submit"
                class="w-full bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                Gửi link đặt lại mật khẩu
            </button>
        </form>

        <!-- Quay lại login -->
        <p class="mt-6 text-center text-sm text-gray-600">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Quay lại đăng nhập</a>
        </p>
    </div>
</x-guest-layout>
