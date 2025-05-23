<?php

namespace App\Http\Controllers;

use App\Repositories\OrderStatisticsRepository;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    protected $statsRepository;

    public function __construct(OrderStatisticsRepository $statsRepository)
    {
        $this->statsRepository = $statsRepository;
    }

    public function index()
    {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('user.dashboard');
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

    public function adminDashboard()
    {
        $dashboardStats = $this->statsRepository->getDashboardStats();
        $monthlyStats = $this->statsRepository->getMonthlyStats();
        $dailyStats = $this->statsRepository->getDailyStats();
        $weeklyStats = $this->statsRepository->getWeeklyStats();

        $monthlyLabels = [];
        $monthlyData = [];
        $monthlyRevenue = [];

        foreach ($monthlyStats as $stat) {
            $monthlyLabels[] = date('M Y', mktime(0, 0, 0, $stat->month, 1, $stat->year));
            $monthlyData[] = $stat->count;
            $monthlyRevenue[] = (float) $stat->revenue ?? 0;
        }

        $dailyLabels = [];
        $dailyData = [];
        $dailyRevenue = [];

        foreach ($dailyStats as $stat) {
            $dailyLabels[] = date('d M', strtotime($stat->date));
            $dailyData[] = $stat->count;
            $dailyRevenue[] = (float) $stat->revenue ?? 0;
        }

        $weeklyLabels = [];
        $weeklyData = [];
        $weeklyRevenue = [];

        foreach ($weeklyStats as $stat) {
            $startOfWeek = Carbon::now()->setISODate($stat->year, $stat->week)->startOfWeek();
            $weeklyLabels[] = $startOfWeek->translatedFormat('d F Y');
            $weeklyData[] = $stat->count;
            $weeklyRevenue[] = (float) $stat->revenue ?? 0;
        }

        $orderStatusData = $this->statsRepository->getOrderStatusStats();

        // Pesanan yang perlu dikonfirmasi admin: SEMUA pesanan baru (pending/processing), baik online maupun cash
        $ordersToConfirm = \App\Models\Order::with(['customer', 'service'])
            ->whereIn('status', ['pending', 'processing'])
            ->latest()
            ->get();

        return view('dashboardAdmin', [
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
            'ordersToConfirm'   => $ordersToConfirm,
        ]);
    }

    public function userDashboard()
    {
        $userName = auth()->user()->name;
        $userId = auth()->id();

        $customerId = \App\Models\Customer::where('name', $userName)
            ->where('user_id', $userId)
            ->value('id');

        $recentOrders = \App\Models\Order::where('customer_id', $customerId)
            ->distinct()
            ->latest()
            ->take(5)
            ->get();

        $totalPrice = \App\Models\Order::where('customer_id', $customerId)
            ->sum('total_price');

        $totalOrders = \App\Models\Order::where('customer_id', $customerId)->count();
        $totalSpent = \App\Models\Order::where('customer_id', $customerId)->sum('total_price');
        $pendingOrders = \App\Models\Order::where('customer_id', $customerId)->where('status', 'pending')->count();

        // Debug log untuk memeriksa pengguna yang sedang login
        Log::info('Logged-in User:', ['id' => auth()->id(), 'email' => auth()->user()->email, 'name' => $userName]);

        // Debug log untuk memeriksa pesanan yang diambil
        Log::info('Recent Orders:', $recentOrders->toArray());
        Log::info('Total Price:', ['totalPrice' => $totalPrice]);

        Log::info('Query Debugging:', [
            'userName' => $userName,
            'userId' => $userId,
            'recentOrders' => $recentOrders->toArray(),
        ]);

        return view('dashboard.user', [
            'recentOrders' => $recentOrders,
            'totalPrice' => $totalPrice,
            'totalOrders' => $totalOrders,
            'totalSpent' => $totalSpent,
            'pendingOrders' => $pendingOrders,
        ]);
    }
}
