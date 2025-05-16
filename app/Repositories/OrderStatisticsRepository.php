<?php

namespace App\Repositories;

use App\Models\Order;
use Carbon\Carbon;

class OrderStatisticsRepository
{
    public function getDashboardStats()
    {
        $now = Carbon::now();
        
        return [
            'totalCustomers' => \App\Models\Customer::count(),
            'monthlyOrders' => Order::whereMonth('created_at', $now->month)
                                ->whereYear('created_at', $now->year)
                                ->count(),
            'monthlyRevenue' => Order::whereMonth('created_at', $now->month)
                                ->whereYear('created_at', $now->year)
                                ->where('status', 'completed')
                                ->sum('total_price'),
            'pendingOrders' => Order::where('status', 'pending')->count(),
            'recentOrders' => Order::with(['customer', 'service'])
                                ->latest()
                                ->take(10)
                                ->get()
        ];
    }

    public function getDailyStats($days = 30)
{
    return Order::query()
        ->selectRaw('DATE(created_at) as date, CAST(COUNT(*) AS UNSIGNED) as count, SUM(total_price) as revenue')
        ->where('created_at', '>=', now()->subDays($days))
        ->where('status', 'completed')
        ->groupBy('date')
        ->orderBy('date')
        ->get();
}

public function getWeeklyStats($weeks = 12)
{
    return Order::query()
        ->selectRaw('YEAR(created_at) as year, WEEK(created_at, 1) as week, COUNT(*) as count, SUM(total_price) as revenue')
        ->where('created_at', '>=', now()->subWeeks($weeks))
        ->where('status', 'completed')
        ->groupBy('year', 'week')
        ->orderBy('year')
        ->orderBy('week')
        ->get();
}

public function getMonthlyStats($months = 12)
{
    return Order::query()
        ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count, SUM(total_price) as revenue')
        ->where('created_at', '>=', now()->subMonths($months))
        ->where('status', 'completed')
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get();
}

    public function getOrderStatusStats()
    {
        return Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();
    }
}