<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsAllTimes extends StatsOverviewWidget
{
    protected static ?int $sort = -1;
    protected function getStats(): array
    {
        // Get transaction counts grouped by month for the chart data
        $monthlyData = Transaction::query()
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month')
            ->selectRaw('COUNT(CASE WHEN status = "done" THEN 1 END) as success_count')
            ->selectRaw('COUNT(CASE WHEN status = "progress" THEN 1 END) as processing_count')
            ->selectRaw('COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_count')
            ->selectRaw('COUNT(CASE WHEN status = "cancel" THEN 1 END) as canceled_count')
            ->selectRaw('COUNT(*) as total_count')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Prepare chart data for each status
        $successChartData = $monthlyData->pluck('success_count')->toArray();
        $processingChartData = $monthlyData->pluck('processing_count')->toArray();
        $pendingChartData = $monthlyData->pluck('pending_count')->toArray();
        $canceledChartData = $monthlyData->pluck('canceled_count')->toArray();
        
        // Limit to the last 7 months for the chart if there are more than 7 months of data
        if (count($successChartData) > 7) {
            $successChartData = array_slice($successChartData, -7);
            $processingChartData = array_slice($processingChartData, -7);
            $pendingChartData = array_slice($pendingChartData, -7);
            $canceledChartData = array_slice($canceledChartData, -7);
        }

        $order_success = Transaction::query()->where('status', '=', 'done')->count();
        $order_processing = Transaction::query()->where('status', '=', 'progress')->count();
        $order_pending = Transaction::query()->where('status', '=', 'pending')->count();
        $order_canceled = Transaction::query()->where('status', '=', 'cancel')->count();

        return [
            Stat::make('Pesanan Selesai', $order_success)
                ->description('Semua periode')
                ->chart($successChartData),
            Stat::make('Pesanan Proses', $order_processing)
                ->description('Semua periode')
                ->chart($processingChartData),
            Stat::make('Pesanan Menunggu', $order_pending)
                ->description('Semua periode')
                ->chart($pendingChartData),
            Stat::make('Pesanan Batal', $order_canceled)
                ->description('Semua periode')
                ->chart($canceledChartData),
        ];
    }
}
