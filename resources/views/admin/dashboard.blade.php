@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-description', 'Overview of your astrology app statistics')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Users -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_users']) }}</p>
                    <p class="text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+12% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-users text-blue-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Astrologers -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Astrologers</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_astrologers']) }}</p>
                    <p class="text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+5% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-purple-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Tasks -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Total Consultations</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_tasks']) }}</p>
                    <p class="text-green-600 text-sm mt-1">
                        <i class="fas fa-arrow-up mr-1"></i>+18% from last month
                    </p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-tasks text-green-600 text-xl"></i>
                </div>
            </div>
        </div>

        <!-- Pending Deposits -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-600 text-sm font-medium">Pending Deposits</p>
                    <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['pending_deposits']) }}</p>
                    <p class="text-orange-600 text-sm mt-1">
                        <i class="fas fa-clock mr-1"></i>Needs attention
                    </p>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-wallet text-orange-600 text-xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Task Status Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Consultation Status</h3>
            <div class="relative h-64">
                <canvas id="taskStatusChart"></canvas>
            </div>
        </div>

        <!-- Monthly Growth Chart -->
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Monthly Growth</h3>
            <div class="relative h-64">
                <canvas id="monthlyGrowthChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Users -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Users</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recent_users as $user)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full flex items-center justify-center">
                                <span class="text-white font-semibold text-sm">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ $user->name }}</p>
                                <p class="text-gray-600 text-sm">{{ $user->phone }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ $user->created_at->diffForHumans() }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No recent users</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Recent Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Recent Consultations</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($recent_tasks as $task)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-tasks text-white text-sm"></i>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">{{ Str::limit($task->description, 30) }}</p>
                                <p class="text-gray-600 text-sm">by {{ $task->user->name ?? 'Unknown' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-600">{{ $task->created_at->diffForHumans() }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                @if($task->status === 'pending') bg-yellow-100 text-yellow-800
                                @elseif($task->status === 'in progress') bg-blue-100 text-blue-800
                                @else bg-green-100 text-green-800 @endif">
                                {{ ucfirst($task->status) }}
                            </span>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-center py-4">No recent consultations</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Task Status Chart
const taskStatusCtx = document.getElementById('taskStatusChart').getContext('2d');
new Chart(taskStatusCtx, {
    type: 'doughnut',
    data: {
        labels: ['Pending', 'In Progress', 'Completed'],
        datasets: [{
            data: [{{ $stats['pending_tasks'] }}, {{ $stats['total_tasks'] - $stats['pending_tasks'] - $stats['completed_tasks'] }}, {{ $stats['completed_tasks'] }}],
            backgroundColor: ['#FCD34D', '#60A5FA', '#34D399'],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

// Monthly Growth Chart
const monthlyGrowthCtx = document.getElementById('monthlyGrowthChart').getContext('2d');
new Chart(monthlyGrowthCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
        datasets: [{
            label: 'Users',
            data: [12, 19, 25, 32, 45, 52],
            borderColor: '#8B5CF6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            tension: 0.4
        }, {
            label: 'Consultations',
            data: [8, 15, 22, 28, 35, 42],
            borderColor: '#EC4899',
            backgroundColor: 'rgba(236, 72, 153, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        },
        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});
</script>
@endsection