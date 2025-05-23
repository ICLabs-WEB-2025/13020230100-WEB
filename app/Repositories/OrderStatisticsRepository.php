<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\Customer;
use Carbon\Carbon;

class OrderStatisticsRepository
{
    public function getDashboardStats()
    {
        $now = Carbon::now();

        // Asumsi: biaya tetap atau biaya operasional ditentukan secara kasar
        $estimatedOperationalCost = 0.3; // 30% dari total revenue

        $totalRevenue = Order::where('status', 'completed')->sum('total_price');
        // Jika unpaid amount ingin menghitung status 'pending' dan 'processing'
        $unpaidAmount = Order::whereIn('status', ['pending', 'processing'])->sum('total_price');
        $netProfit = $totalRevenue * (1 - $estimatedOperationalCost);

        return [
            'totalCustomers'  => Customer::count(),
            // Konsisten: hanya order completed yang dihitung
            'monthlyOrders'   => Order::whereMonth('created_at', $now->month)
                                      ->whereYear('created_at', $now->year)
                                      ->where('status', 'completed')
                                      ->count(),
            'monthlyRevenue'  => Order::whereMonth('created_at', $now->month)
                                      ->whereYear('created_at', $now->year)
                                      ->where('status', 'completed')
                                      ->sum('total_price'),
            'pendingOrders'   => Order::where('status', 'pending')->count(),
            'recentOrders'    => Order::with(['customer', 'service'])
                                      ->latest()
                                      ->take(10)
                                      ->get(),
            'totalRevenue'    => $totalRevenue,
            'unpaidAmount'    => $unpaidAmount,
            'netProfit'       => $netProfit,
        ];
    }

    public function getDailyStats($days = 30)
    {
        return Order::query()
            ->selectRaw('DATE(created_at) as date, CAST(COUNT(*) AS UNSIGNED) as count, SUM(total_price) as revenue, SUM(CASE WHEN status IN (\'pending\', \'processing\') THEN total_price ELSE 0 END) as unpaid')
            ->where('created_at', '>=', now()->subDays($days))
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }

    public function getWeeklyStats($weeks = 12)
    {
        return Order::query()
            ->selectRaw('YEAR(created_at) as year, WEEK(created_at, 1) as week, COUNT(*) as count, SUM(total_price) as revenue, SUM(CASE WHEN status IN (\'pending\', \'processing\') THEN total_price ELSE 0 END) as unpaid')
            ->where('created_at', '>=', now()->subWeeks($weeks))
            ->whereIn('status', ['completed', 'pending', 'processing'])
            ->groupBy('year', 'week')
            ->orderBy('year')
            ->orderBy('week')
            ->get();
    }

    public function getMonthlyStats($months = 12)
    {
        return Order::query()
            ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as count, SUM(total_price) as revenue, SUM(CASE WHEN status IN (\'pending\', \'processing\') THEN total_price ELSE 0 END) as unpaid')
            ->where('created_at', '>=', now()->subMonths($months))
            ->whereIn('status', ['completed', 'pending', 'processing'])
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
