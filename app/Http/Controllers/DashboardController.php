<?php

namespace App\Http\Controllers;

use App\Repositories\OrderStatisticsRepository;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $statsRepository;

    public function __construct(OrderStatisticsRepository $statsRepository)
    {
        $this->statsRepository = $statsRepository;
    }

    public function index()
    {
        $dashboardStats = $this->statsRepository->getDashboardStats();
        $orderStatusStats = $this->statsRepository->getOrderStatusStats();
        $monthlyStats = $this->statsRepository->getMonthlyStats();
        $dailyStats = $this->statsRepository->getDailyStats();
        $weeklyStats = $this->statsRepository->getWeeklyStats();

        // Format data bulanan
        $monthlyLabels = [];
        $monthlyData = [];
        $monthlyRevenue = [];

        foreach ($monthlyStats as $stat) {
            $monthlyLabels[] = date('M Y', mktime(0, 0, 0, $stat->month, 1, $stat->year));
            $monthlyData[] = $stat->count;
            $monthlyRevenue[] = (float) $stat->revenue ?? 0;
        }

        // Format data harian
        $dailyLabels = [];
        $dailyData = [];
        $dailyRevenue = [];

        foreach ($dailyStats as $stat) {
            $dailyLabels[] = date('d M', strtotime($stat->date));
            $dailyData[] = $stat->count;
            $dailyRevenue[] = (float) $stat->revenue ?? 0;
        }

        // Format data mingguan
        $weeklyLabels = [];
        $weeklyData = [];
        $weeklyRevenue = [];

        foreach ($weeklyStats as $stat) {
            $weeklyLabels[] = 'Minggu ' . $stat->week . ' ' . $stat->year;
            $weeklyData[] = $stat->count;
            $weeklyRevenue[] = (float) $stat->revenue ?? 0;
        }

        // Data status order
        $orderStatusData = [
            $orderStatusStats['completed'] ?? 0,
            $orderStatusStats['processing'] ?? 0,
            $orderStatusStats['pending'] ?? 0
        ];

        return view('dashboard', [
            'totalCustomers' => $dashboardStats['totalCustomers'] ?? 0,
            'monthlyOrders' => $dashboardStats['monthlyOrders'] ?? 0,
            'monthlyRevenue' => $dashboardStats['monthlyRevenue'] ?? 0,
            'pendingOrders' => $dashboardStats['pendingOrders'] ?? 0,
            'recentOrders' => $dashboardStats['recentOrders'] ?? [],
            'monthlyLabels' => $monthlyLabels,
            'monthlyData' => $monthlyData,
            'monthlyRevenueData' => $monthlyRevenue,
            'dailyLabels' => $dailyLabels,
            'dailyData' => $dailyData,
            'dailyRevenueData' => $dailyRevenue,
            'weeklyLabels' => $weeklyLabels,
            'weeklyData' => $weeklyData,
            'weeklyRevenueData' => $weeklyRevenue,
            'orderStatusData' => $orderStatusData
        ]);
    }

    public function getStats(Request $request)
    {
        $period = $request->query('period', 'daily');
        $type = $request->query('type', 'order'); // ⬅️ ini ditambahkan

        $stats = [];

        switch ($period) {
            case 'weekly':
                $stats = $this->statsRepository->getWeeklyStats();
                $labels = [];
                $data = [];

                foreach ($stats as $stat) {
                    $label = 'Minggu ' . $stat->week . ' ' . $stat->year;
                    $labels[] = $label;
                    $data[] = $type === 'order' ? (int) $stat->count : (float) $stat->revenue ?? 0;
                }
                break;

            case 'monthly':
                $stats = $this->statsRepository->getMonthlyStats();
                $labels = [];
                $data = [];

                foreach ($stats as $stat) {
                    $label = date('M Y', mktime(0, 0, 0, $stat->month, 1, $stat->year));
                    $labels[] = $label;
                    $data[] = $type === 'order' ? (int) $stat->count : (float) $stat->revenue ?? 0;
                }
                break;

            case 'daily':
            default:
                $stats = $this->statsRepository->getDailyStats();
                $labels = [];
                $data = [];

                foreach ($stats as $stat) {
                    $label = date('d M', strtotime($stat->date));
                    $labels[] = $label;
                    $data[] = $type === 'order' ? (int) $stat->count : (float) $stat->revenue ?? 0;
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
}
}
