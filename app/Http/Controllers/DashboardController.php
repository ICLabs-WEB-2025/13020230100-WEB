<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Service;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Customer statistics
        $totalCustomers = Customer::count();
        
        // Order statistics
        $monthlyOrders = Order::whereMonth('created_at', now()->month)->count();
        $monthlyRevenue = Order::whereMonth('created_at', now()->month)->sum('total_price');
        $pendingOrders = Order::where('status', 'pending')->count();
        
        // Monthly chart data
        $monthlyLabels = [];
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthlyLabels[] = $date->format('M Y');
            $monthlyData[] = Order::whereYear('created_at', $date->year)
                                ->whereMonth('created_at', $date->month)
                                ->count();
        }
        
        // Order status data
        $orderStatusData = [
            Order::where('status', 'completed')->count(),
            Order::where('status', 'processing')->count(),
            Order::where('status', 'pending')->count(),
        ];
        
        // Recent orders
        $recentOrders = Order::with(['customer', 'service'])
                            ->latest()
                            ->take(5)
                            ->get();

        return view('dashboard', compact(
            'totalCustomers',
            'monthlyOrders',
            'monthlyRevenue',
            'pendingOrders',
            'monthlyLabels',
            'monthlyData',
            'orderStatusData',
            'recentOrders'
        ));
    }
}