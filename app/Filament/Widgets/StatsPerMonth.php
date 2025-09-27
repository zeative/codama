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

        return [
            Stat::make('Pesanan Selesai', $order_success)
                ->description('Dalam bulan ini'),
            Stat::make('Pesanan Proses', $order_processing)
                ->description('Dalam bulan ini'),
            Stat::make('Pesanan Menunggu', $order_pending)
                ->description('Dalam bulan ini'),
            Stat::make('Pesanan Batal', $order_canceled)
                ->description('Dalam bulan ini'),
        ];
    }
}
