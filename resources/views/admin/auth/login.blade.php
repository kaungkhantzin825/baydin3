<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Astrology App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gradient-to-br from-purple-900 via-blue-900 to-indigo-900 min-h-screen flex items-center justify-center">
    <div class="absolute inset-0 bg-black opacity-50"></div>
    
    <!-- Stars Animation -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="stars"></div>
        <div class="stars2"></div>
        <div class="stars3"></div>
    </div>

    <div class="relative z-10 w-full max-w-md">
        <div class="bg-white/10 backdrop-blur-lg rounded-2xl shadow-2xl p-8 border border-white/20">
            <!-- Logo/Header -->
            <div class="text-center mb-8">
                <div class="mx-auto w-20 h-20 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-star text-white text-2xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">Admin Panel</h1>
                <p class="text-purple-200">Astrology App Management</p>
            </div>

            <!-- Login Form -->
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf
                
                <!-- Phone Input -->
                <div>
                    <label class="block text-sm font-medium text-purple-200 mb-2">
                        <i class="fas fa-phone mr-2"></i>Phone Number
                    </label>
                    <input type="text" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all"
                           placeholder="Enter your phone number"
                           required>
                    @error('phone')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <label class="block text-sm font-medium text-purple-200 mb-2">
                        <i class="fas fa-lock mr-2"></i>Password
                    </label>
                    <div class="relative">
                        <input type="password" 
                               name="password" 
                               id="password"
                               class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-lg text-white placeholder-purple-300 focus:outline-none focus:ring-2 focus:ring-purple-400 focus:border-transparent transition-all pr-12"
                               placeholder="Enter your password"
                               required>
                        <button type="button" 
                                onclick="togglePassword()"
                                class="absolute right-3 top-1/2 transform -translate-y-1/2 text-purple-300 hover:text-white transition-colors">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-purple-400">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login to Admin Panel
                </button>
            </form>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-purple-300 text-sm">
                    <i class="fas fa-shield-alt mr-1"></i>
                    Secure Admin Access Only
                </p>
            </div>
        </div>
    </div>

    <style>
        .stars, .stars2, .stars3 {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: transparent;
        }

        .stars {
            background-image: 
                radial-gradient(2px 2px at 20px 30px, #eee, transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255,255,255,0.8), transparent),
                radial-gradient(1px 1px at 90px 40px, #fff, transparent);
            background-repeat: repeat;
            background-size: 200px 100px;
            animation: zoom 20s infinite;
            opacity: 0.4;
        }

        .stars2 {
            background-image: 
                radial-gradient(1px 1px at 40px 60px, #fff, transparent),
                radial-gradient(1px 1px at 120px 10px, rgba(255,255,255,0.6), transparent);
            background-repeat: repeat;
            background-size: 300px 200px;
            animation: zoom 40s infinite;
            opacity: 0.3;
        }

        .stars3 {
            background-image: 
                radial-gradient(1px 1px at 10px 10px, #fff, transparent),
                radial-gradient(1px 1px at 150px 150px, rgba(255,255,255,0.5), transparent);
            background-repeat: repeat;
            background-size: 400px 300px;
            animation: zoom 60s infinite;
            opacity: 0.2;
        }

        @keyframes zoom {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }
    </style>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>