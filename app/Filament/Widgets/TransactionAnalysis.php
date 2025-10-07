<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Color;
use Carbon\Carbon;
use DB;
use Filament\Support\Colors\Color as SupportColor;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class TransactionAnalysis extends StatsOverviewWidget
{
    protected static ?int $sort = 3;

    protected function getStats(): array
    {
        // Get stats for current user
        $userId = auth()->id();

        // Total transactions value for all time
        $totalValue = Transaction::where('user_id', $userId)
            ->sum(DB::raw('product_amount * product_count'));

        // Total completed transactions
        $completedCount = Transaction::where('user_id', $userId)
            ->where('status', 'done')
            ->count();

        // Total transactions in progress
        $inProgressCount = Transaction::where('user_id', $userId)
            ->whereIn('status', ['progress', 'pending'])
            ->count();

        // Average transaction value
        $avgTransactionValue = Transaction::where('user_id', $userId)
            ->avg(DB::raw('product_amount * product_count')) ?? 0;

        // Get transaction data for monthly chart (last 6 months)
        $monthlyData = Transaction::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(product_amount * product_count) as total_amount'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->where('user_id', $userId)
            ->whereBetween('created_at', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()])
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy('month')
            ->get();

        // Prepare chart data for the last 6 months
        $months = [];
        $monthlyAmounts = [];
        $monthlyCounts = [];

        $fiveMonthsAgo = Carbon::now()->subMonths(5);
        for ($i = 0; $i < 6; $i++) {
            $month = $fiveMonthsAgo->copy()->addMonths($i)->format('Y-m');
            $months[] = $fiveMonthsAgo->copy()->addMonths($i)->format('M');

            $monthlyRecord = $monthlyData->firstWhere('month', $month);
            $monthlyAmounts[] = $monthlyRecord ? (int)$monthlyRecord->total_amount : 0;
            $monthlyCounts[] = $monthlyRecord ? (int)$monthlyRecord->transaction_count : 0;
        }

        // Get top 3 categories by transaction value
        $topCategories = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                'categories.name',
                DB::raw('SUM(transactions.product_amount * transactions.product_count) as total_value'),
                DB::raw('COUNT(transactions.id) as transaction_count')
            )
            ->where('transactions.user_id', $userId)
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_value', 'desc')
            ->limit(3)
            ->get();

        // Calculate completion rate
        $allTransactionsCount = Transaction::where('user_id', $userId)->count();
        $completionRate = $allTransactionsCount > 0 
            ? round(($completedCount / $allTransactionsCount) * 100, 2) 
            : 0;

        return [
            Stat::make('Total Transaction Value', Number::currency($totalValue, 'IDR', 'id', 0))
                ->description('All time')
                ->chart($monthlyAmounts)
                ->color('success'),

            Stat::make('Completed Transactions', $completedCount)
                ->description('All time')
                ->chart($monthlyCounts)
                ->color('info'),

            Stat::make('Completion Rate', $completionRate . '%')
                ->description('Of all transactions')
                ->chart([
                    min(100, $completionRate), 
                    100 - min(100, $completionRate)
                ])
                ->color($completionRate >= 80 ? 'success' : ($completionRate >= 50 ? 'warning' : 'danger')),
        ];
    }
}