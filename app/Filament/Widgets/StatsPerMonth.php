<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsPerMonth extends StatsOverviewWidget
{

    protected static ?int $sort = -1;

    protected function getStats(): array
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $baseQuery = Transaction::query()
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth]);

        $order_success = (clone $baseQuery)->where('status', '=', 'done')->count();
        $order_processing = (clone $baseQuery)->where('status', '=', 'progress')->count();
        $order_pending = (clone $baseQuery)->where('status', '=', 'pending')->count();
        $order_canceled = (clone $baseQuery)->where('status', '=', 'cancel')->count();

        // Get weekly data for the current month for chart
        $weeklyData = Transaction::query()
            ->selectRaw('WEEK(created_at) as week_number')
            ->selectRaw('COUNT(CASE WHEN status = "done" THEN 1 END) as success_count')
            ->selectRaw('COUNT(CASE WHEN status = "progress" THEN 1 END) as processing_count')
            ->selectRaw('COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_count')
            ->selectRaw('COUNT(CASE WHEN status = "cancel" THEN 1 END) as canceled_count')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy('week_number')
            ->orderBy('week_number')
            ->get();

        // Prepare chart data for each status
        $successChartData = $weeklyData->pluck('success_count')->toArray();
        $processingChartData = $weeklyData->pluck('processing_count')->toArray();
        $pendingChartData = $weeklyData->pluck('pending_count')->toArray();
        $canceledChartData = $weeklyData->pluck('canceled_count')->toArray();

        // Ensure we have at least some data for chart
        if (empty($successChartData)) {
            $successChartData = [0];
        }
        if (empty($processingChartData)) {
            $processingChartData = [0];
        }
        if (empty($pendingChartData)) {
            $pendingChartData = [0];
        }
        if (empty($canceledChartData)) {
            $canceledChartData = [0];
        }

        return [
            Stat::make('Pesanan Selesai', $order_success)
                ->description('Dalam bulan ini')
                ->chart($successChartData),
            Stat::make('Pesanan Proses', $order_processing)
                ->description('Dalam bulan ini')
                ->chart($processingChartData),
            Stat::make('Pesanan Menunggu', $order_pending)
                ->description('Dalam bulan ini')
                ->chart($pendingChartData),
            Stat::make('Pesanan Batal', $order_canceled)
                ->description('Dalam bulan ini')
                ->chart($canceledChartData),
        ];
    }
}
