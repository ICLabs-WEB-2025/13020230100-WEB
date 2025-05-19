<?php

namespace App\Http\Controllers;

use App\Repositories\OrderStatisticsRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;

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
            $startOfWeek = Carbon::now()->setISODate($stat->year, $stat->week)->startOfWeek();
            $weeklyLabels[] = $startOfWeek->translatedFormat('d F Y'); // e.g., 05 Mei 2025
            $weeklyData[] = $stat->count;
            $weeklyRevenue[] = (float) $stat->revenue ?? 0;
        }

        $orderStatusData = $this->statsRepository->getOrderStatusStats(); // Menggunakan kunci status seperti 'pending', 'processing', 'completed', dan 'cancelled'

        return view('dashboard', [
            'totalCustomers'    => $dashboardStats['totalCustomers'] ?? 0,
            'monthlyOrders'     => $dashboardStats['monthlyOrders'] ?? 0,
            'monthlyRevenue'    => $dashboardStats['monthlyRevenue'] ?? 0,
            'pendingOrders'     => $dashboardStats['pendingOrders'] ?? 0,
            'recentOrders'      => $dashboardStats['recentOrders'] ?? [],
            'totalRevenue'      => $dashboardStats['totalRevenue'] ?? 0,
            'unpaidAmount'      => $dashboardStats['unpaidAmount'] ?? 0,
            'netProfit'         => $dashboardStats['netProfit'] ?? 0,
            'monthlyLabels'     => $monthlyLabels,
            'monthlyData'       => $monthlyData,
            'monthlyRevenueData'=> $monthlyRevenue,
            'dailyLabels'       => $dailyLabels,
            'dailyData'         => $dailyData,
            'dailyRevenueData'  => $dailyRevenue,
            'weeklyLabels'      => $weeklyLabels,
            'weeklyData'        => $weeklyData,
            'weeklyRevenueData' => $weeklyRevenue,
            'orderStatusData'   => $orderStatusData,
        ]);
    }

    public function getStats(Request $request)
    {
        $period = $request->query('period', 'daily');
        $type = $request->query('type', 'order');
        $revenueType = $request->query('revenue_type', 'total');

        $stats = [];
        $labels = [];
        $data = [];

        switch ($period) {
            case 'weekly':
                $stats = $this->statsRepository->getWeeklyStats();
                foreach ($stats as $stat) {
                    $startOfWeek = Carbon::now()->setISODate($stat->year, $stat->week)->startOfWeek();
                    $labels[] = $startOfWeek->translatedFormat('d F Y');
                    if ($type === 'order') {
                        $data[] = (int) $stat->count;
                    } else {
                        if ($revenueType === 'total') {
                            $data[] = (float) ($stat->revenue ?? 0);
                        } elseif ($revenueType === 'unpaid') {
                            $data[] = (float) ($stat->unpaid ?? 0);
                        } elseif ($revenueType === 'profit') {
                            $operationalCost = 0.3;
                            $data[] = (float) ($stat->revenue ?? 0) * (1 - $operationalCost);
                        }
                    }
                }
                break;
            case 'monthly':
                $stats = $this->statsRepository->getMonthlyStats();
                foreach ($stats as $stat) {
                    $labels[] = date('M Y', mktime(0, 0, 0, $stat->month, 1, $stat->year));
                    if ($type === 'order') {
                        $data[] = (int) $stat->count;
                    } else {
                        if ($revenueType === 'total') {
                            $data[] = (float) ($stat->revenue ?? 0);
                        } elseif ($revenueType === 'unpaid') {
                            $data[] = (float) ($stat->unpaid ?? 0);
                        } elseif ($revenueType === 'profit') {
                            $operationalCost = 0.3;
                            $data[] = (float) ($stat->revenue ?? 0) * (1 - $operationalCost);
                        }
                    }
                }
                break;
            case 'daily':
            default:
                $stats = $this->statsRepository->getDailyStats();
                foreach ($stats as $stat) {
                    $labels[] = date('d M Y', strtotime($stat->date));
                    if ($type === 'order') {
                        $data[] = (int) $stat->count;
                    } else {
                        if ($revenueType === 'total') {
                            $data[] = (float) ($stat->revenue ?? 0);
                        } elseif ($revenueType === 'unpaid') {
                            $data[] = (float) ($stat->unpaid ?? 0);
                        } elseif ($revenueType === 'profit') {
                            $operationalCost = 0.3;
                            $data[] = (float) ($stat->revenue ?? 0) * (1 - $operationalCost);
                        }
                    }
                }
                break;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $data,
        ]);
    }
}
