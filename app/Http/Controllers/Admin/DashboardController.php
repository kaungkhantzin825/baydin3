<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Astrologer;
use App\Models\Toask;
use App\Models\InMoney;
use App\Models\FreeBaydin;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users' => User::where('role', 'customer')->count(),
            'total_astrologers' => User::where('role', 'astrology')->count(),
            'total_tasks' => Toask::count(),
            'pending_tasks' => Toask::where('status', 'pending')->count(),
            'completed_tasks' => Toask::where('status', 'completed')->count(),
            'pending_deposits' => InMoney::where('status', 'pending')->count(),
            'total_consultations' => FreeBaydin::count(),
        ];

        $recent_users = User::where('role', 'customer')
            ->latest()
            ->take(5)
            ->get();

        $recent_tasks = Toask::with(['user', 'astrologer', 'category'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_users', 'recent_tasks'));
    }
}