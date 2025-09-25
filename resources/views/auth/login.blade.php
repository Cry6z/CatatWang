<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login - CatatWang</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased bg-gradient-to-br from-primary-50 to-blue-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Logo and Title -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-600 rounded-2xl mb-4">
                <i class="fas fa-wallet text-white text-2xl"></i>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">CatatWang</h1>
            <p class="text-gray-600">Manajemen Keuangan Kelas</p>
        </div>

        <!-- Login Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            <div class="mb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Masuk ke Akun</h2>
                <p class="text-gray-600">Silakan masuk untuk melanjutkan</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" 
                               type="email" 
                               name="email" 
                               value="{{ old('email') }}" 
                               required 
                               autocomplete="email" 
                               autofocus
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('email') border-red-500 @enderror"
                               placeholder="Masukkan email Anda">
                    </div>
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               required 
                               autocomplete="current-password"
                               class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 @error('password') border-red-500 @enderror"
                               placeholder="Masukkan password Anda">
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" 
                               type="checkbox" 
                               name="remember" 
                               {{ old('remember') ? 'checked' : '' }}
                               class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-700">
                            Ingat saya
                        </label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-primary-600 hover:text-primary-500">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
            </form>

            <!-- Demo Accounts -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-600 mb-4 text-center">Akun Demo:</p>
                <div class="grid grid-cols-1 gap-2 text-xs">
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="font-medium text-gray-700">Admin</div>
                        <div class="text-gray-600">admin@catatwang.com / admin123</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="font-medium text-gray-700">Bendahara</div>
                        <div class="text-gray-600">bendahara@catatwang.com / admin123</div>
                    </div>
                    <div class="bg-gray-50 p-3 rounded-lg">
                        <div class="font-medium text-gray-700">Anggota</div>
                        <div class="text-gray-600">anggota1@catatwang.com / admin123</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-sm text-gray-600">
            <p>&copy; {{ date('Y') }} CatatWang. Sistem Manajemen Keuangan Kelas.</p>
        </div>
    </div>
</body>
</html>
