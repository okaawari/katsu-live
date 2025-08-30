<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentHistory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionController extends Controller
{
    /**
     * Display transaction analytics dashboard.
     */
    public function index(Request $request)
    {
        // Get filter parameters
        $period = $request->get('period', 'month'); // day, week, month, year
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $userId = $request->get('user_id');
        $minAmount = $request->get('min_amount');
        $maxAmount = $request->get('max_amount');

        // Set default date range if not provided
        if (!$startDate || !$endDate) {
            switch ($period) {
                case 'day':
                    $startDate = now()->subDays(30)->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
                case 'week':
                    $startDate = now()->subWeeks(12)->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
                case 'year':
                    $startDate = now()->subYears(2)->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
                default: // month
                    $startDate = now()->subMonths(12)->format('Y-m-d');
                    $endDate = now()->format('Y-m-d');
                    break;
            }
        }

        // Build base query - use created_at instead of transaction_date
        $query = PaymentHistory::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);

        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($minAmount) {
            $query->where('amount', '>=', $minAmount);
        }

        if ($maxAmount) {
            $query->where('amount', '<=', $maxAmount);
        }

        // Get overall statistics
        $stats = $this->getOverallStats($startDate, $endDate, $userId, $minAmount, $maxAmount);
        
        // Get chart data based on period
        $chartData = $this->getChartData($period, $startDate, $endDate, $userId, $minAmount, $maxAmount);
        
        // Get recent transactions
        $recentTransactions = $query->clone()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Get top users by revenue
        $topUsers = $this->getTopUsersByRevenue($startDate, $endDate);
        
        // Get monthly growth data
        $monthlyGrowth = $this->getMonthlyGrowthData();
        
        // Get users for filter dropdown
        $users = User::whereHas('paymentHistories')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('admin.transactions.index', compact(
            'stats',
            'chartData',
            'recentTransactions',
            'topUsers',
            'monthlyGrowth',
            'users',
            'period',
            'startDate',
            'endDate',
            'userId',
            'minAmount',
            'maxAmount'
        ));
    }

    /**
     * Get overall transaction statistics.
     */
    private function getOverallStats($startDate, $endDate, $userId = null, $minAmount = null, $maxAmount = null)
    {
        $query = PaymentHistory::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($minAmount) {
            $query->where('amount', '>=', $minAmount);
        }

        if ($maxAmount) {
            $query->where('amount', '<=', $maxAmount);
        }

        $totalRevenue = $query->sum('amount');
        $totalTransactions = $query->count();
        $averageTransaction = $totalTransactions > 0 ? $totalRevenue / $totalTransactions : 0;
        $uniqueCustomers = $query->distinct('user_id')->count('user_id');

        // Get previous period for comparison
        $periodLength = Carbon::parse($endDate)->diffInDays(Carbon::parse($startDate));
        $prevStartDate = Carbon::parse($startDate)->subDays($periodLength)->format('Y-m-d');
        $prevEndDate = Carbon::parse($startDate)->subDay()->format('Y-m-d');

        $prevQuery = PaymentHistory::whereBetween('created_at', [$prevStartDate . ' 00:00:00', $prevEndDate . ' 23:59:59']);
        if ($userId) {
            $prevQuery->where('user_id', $userId);
        }

        $prevRevenue = $prevQuery->sum('amount');
        $prevTransactions = $prevQuery->count();

        $revenueGrowth = $prevRevenue > 0 ? (($totalRevenue - $prevRevenue) / $prevRevenue) * 100 : 0;
        $transactionGrowth = $prevTransactions > 0 ? (($totalTransactions - $prevTransactions) / $prevTransactions) * 100 : 0;

        return [
            'total_revenue' => $totalRevenue,
            'total_transactions' => $totalTransactions,
            'average_transaction' => $averageTransaction,
            'unique_customers' => $uniqueCustomers,
            'revenue_growth' => $revenueGrowth,
            'transaction_growth' => $transactionGrowth,
        ];
    }

    /**
     * Get chart data based on period.
     */
    private function getChartData($period, $startDate, $endDate, $userId = null, $minAmount = null, $maxAmount = null)
    {
        $query = PaymentHistory::whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59']);
        
        if ($userId) {
            $query->where('user_id', $userId);
        }

        if ($minAmount) {
            $query->where('amount', '>=', $minAmount);
        }

        if ($maxAmount) {
            $query->where('amount', '<=', $maxAmount);
        }

        switch ($period) {
            case 'day':
                return $query->select(
                    DB::raw('DATE(created_at) as period'),
                    DB::raw('SUM(amount) as revenue'),
                    DB::raw('COUNT(*) as transactions')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            case 'week':
                return $query->select(
                    DB::raw('YEARWEEK(created_at) as period'),
                    DB::raw('SUM(amount) as revenue'),
                    DB::raw('COUNT(*) as transactions'),
                    DB::raw('MIN(DATE(created_at)) as start_date'),
                    DB::raw('MAX(DATE(created_at)) as end_date')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            case 'year':
                return $query->select(
                    DB::raw('YEAR(created_at) as period'),
                    DB::raw('SUM(amount) as revenue'),
                    DB::raw('COUNT(*) as transactions')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();

            default: // month
                return $query->select(
                    DB::raw('DATE_FORMAT(created_at, "%Y-%m") as period'),
                    DB::raw('SUM(amount) as revenue'),
                    DB::raw('COUNT(*) as transactions')
                )
                ->groupBy('period')
                ->orderBy('period')
                ->get();
        }
    }

    /**
     * Get top users by revenue.
     */
    private function getTopUsersByRevenue($startDate, $endDate, $limit = 10)
    {
        return PaymentHistory::with('user')
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select('user_id', DB::raw('SUM(amount) as total_revenue'), DB::raw('COUNT(*) as transaction_count'))
            ->groupBy('user_id')
            ->orderBy('total_revenue', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get monthly growth data for the last 12 months.
     */
    private function getMonthlyGrowthData()
    {
        $startDate = now()->subMonths(12)->startOfMonth();
        
        return PaymentHistory::where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(amount) as revenue'),
                DB::raw('COUNT(*) as transactions'),
                DB::raw('COUNT(DISTINCT user_id) as unique_customers')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Export transactions data.
     */
    public function export(Request $request)
    {
        $validated = $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
            'format' => 'required|in:csv,json',
        ]);

        $query = PaymentHistory::with('user')
            ->whereBetween('created_at', [$validated['start_date'] . ' 00:00:00', $validated['end_date'] . ' 23:59:59']);

        if ($validated['user_id']) {
            $query->where('user_id', $validated['user_id']);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        if ($validated['format'] === 'csv') {
            return $this->exportToCsv($transactions);
        }

        return response()->json($transactions);
    }

    /**
     * Export transactions to CSV.
     */
    private function exportToCsv($transactions)
    {
        $filename = 'transactions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'ID',
                'Transaction Date',
                'Amount',
                'User Name',
                'User Email',
                'Code',
                'Reference ID',
                'Created At'
            ]);

            // CSV data
            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->id,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->amount,
                    $transaction->user?->name,
                    $transaction->user?->email,
                    $transaction->code,
                    $transaction->refId,
                    $transaction->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get transaction details for API.
     */
    public function apiData(Request $request)
    {
        $validated = $request->validate([
            'period' => 'required|in:day,week,month,year',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $chartData = $this->getChartData(
            $validated['period'],
            $validated['start_date'],
            $validated['end_date'],
            $validated['user_id']
        );

        $stats = $this->getOverallStats(
            $validated['start_date'],
            $validated['end_date'],
            $validated['user_id']
        );

        return response()->json([
            'chart_data' => $chartData,
            'stats' => $stats,
        ]);
    }
}
