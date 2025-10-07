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
            ->where('status', 'progress')
            ->count();

        // Total pending transactions
        $pendingCount = Transaction::where('user_id', $userId)
            ->where('status', 'pending')
            ->count();

        // Total cancelled transactions
        $cancelledCount = Transaction::where('user_id', $userId)
            ->where('status', 'cancel')
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

        // Calculate completion rate
        $allTransactionsCount = Transaction::where('user_id', $userId)->count();
        $completionRate = $allTransactionsCount > 0 
            ? round(($completedCount / $allTransactionsCount) * 100, 2) 
            : 0;

        // Calculate average transaction value
        $formattedAvgValue = $avgTransactionValue > 0 
            ? Number::currency($avgTransactionValue, 'IDR', 'id', 0) 
            : 'Rp 0';

        return [
            Stat::make('💰 Total Revenue', Number::currency($totalValue, 'IDR', 'id', 0))
                ->description('All transactions')
                ->chart($monthlyAmounts)
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'title' => 'Total revenue from all transactions'
                ]),

            Stat::make('✅ Completed', $completedCount)
                ->description('Successfully completed')
                ->chart($monthlyCounts)
                ->color('success')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'title' => 'Total number of successfully completed transactions'
                ]),

            Stat::make('📊 Completion Rate', $completionRate . '%')
                ->description('Overall completion rate')
                ->chart([
                    min(100, $completionRate), 
                    100 - min(100, $completionRate)
                ])
                ->color($completionRate >= 80 ? 'success' : ($completionRate >= 50 ? 'warning' : 'danger'))
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'title' => 'Percentage of completed vs total transactions'
                ]),
        ];
    }
}