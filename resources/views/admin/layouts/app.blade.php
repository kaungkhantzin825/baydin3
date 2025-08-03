<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Astrology App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-gradient-to-b from-purple-800 to-indigo-900 text-white w-64 flex-shrink-0">
            <!-- Logo -->
            <div class="p-6 border-b border-purple-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-pink-400 rounded-lg flex items-center justify-center mr-3">
                        <i class="fas fa-star text-white"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold">Astrology App</h1>
                        <p class="text-purple-300 text-sm">Admin Panel</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <nav class="mt-6">
                <div class="px-6 mb-6">
                    <p class="text-purple-300 text-xs uppercase tracking-wider font-semibold">Main Menu</p>
                </div>
                
                <a href="{{ route('admin.dashboard') }}" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors {{ request()->routeIs('admin.dashboard') ? 'bg-purple-700 border-r-4 border-purple-400' : '' }}">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.users.index') }}" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors {{ request()->routeIs('admin.users.*') ? 'bg-purple-700 border-r-4 border-purple-400' : '' }}">
                    <i class="fas fa-users mr-3"></i>
                    Users Management
                </a>
                
                <a href="{{ route('admin.consultations.index') }}" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors {{ request()->routeIs('admin.consultations.*') ? 'bg-purple-700 border-r-4 border-purple-400' : '' }}">
                    <i class="fas fa-tasks mr-3"></i>
                    Consultations
                </a>
                
                <div class="px-6 py-2">
                    <p class="text-purple-300 text-xs uppercase tracking-wider font-semibold">Financial</p>
                </div>
                
                <a href="{{ route('admin.financial.deposits') }}" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors {{ request()->routeIs('admin.financial.deposits') ? 'bg-purple-700 border-r-4 border-purple-400' : '' }}">
                    <i class="fas fa-money-check-alt mr-3"></i>
                    Deposit Requests
                </a>
                
                <a href="{{ route('admin.financial.wallets') }}" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors {{ request()->routeIs('admin.financial.wallets') ? 'bg-purple-700 border-r-4 border-purple-400' : '' }}">
                    <i class="fas fa-wallet mr-3"></i>
                    User Wallets
                </a>
                
                <a href="{{ route('admin.financial.transactions') }}" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors {{ request()->routeIs('admin.financial.transactions') ? 'bg-purple-700 border-r-4 border-purple-400' : '' }}">
                    <i class="fas fa-exchange-alt mr-3"></i>
                    Transactions
                </a>
                
                <a href="{{ route('admin.financial.api-management') }}" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors {{ request()->routeIs('admin.financial.api-management') ? 'bg-purple-700 border-r-4 border-purple-400' : '' }}">
                    <i class="fas fa-code mr-3"></i>
                    API Management
                </a>
                
                <a href="{{ route('admin.categories.index') }}" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors {{ request()->routeIs('admin.categories.*') ? 'bg-purple-700 border-r-4 border-purple-400' : '' }}">
                    <i class="fas fa-list mr-3"></i>
                    Categories
                </a>
                
                <a href="{{ route('admin.free-consultations.index') }}" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors {{ request()->routeIs('admin.free-consultations.*') ? 'bg-purple-700 border-r-4 border-purple-400' : '' }}">
                    <i class="fas fa-gift mr-3"></i>
                    Free Consultations
                </a>

                <div class="px-6 mt-8 mb-4">
                    <p class="text-purple-300 text-xs uppercase tracking-wider font-semibold">System</p>
                </div>
                
                <a href="#" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors">
                    <i class="fas fa-cog mr-3"></i>
                    Settings
                </a>
                
                <a href="#" class="flex items-center px-6 py-3 text-purple-100 hover:bg-purple-700 hover:text-white transition-colors">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Reports
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Header -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-gray-600 text-sm">@yield('page-description', 'Welcome to your admin dashboard')</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <div class="relative">
                            <button class="p-2 text-gray-400 hover:text-gray-600 relative">
                                <i class="fas fa-bell text-xl"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">3</span>
                            </button>
                        </div>

                        <!-- User Menu -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center space-x-3 text-gray-700 hover:text-gray-900">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                                    <i class="fas fa-user text-white text-sm"></i>
                                </div>
                                <span class="font-medium">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>
                            
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profile
                                </a>
                                <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-cog mr-2"></i>Settings
                                </a>
                                <hr class="my-2">
                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>