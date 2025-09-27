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
        $order_success = Transaction::query()->where('status', '=', 'done')->count();
        $order_processing = Transaction::query()->where('status', '=', 'progress')->count();
        $order_pending = Transaction::query()->where('status', '=', 'pending')->count();
        $order_canceled = Transaction::query()->where('status', '=', 'cancel')->count();

        return [
            Stat::make('Pesanan Selesai', $order_success)
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
            Stat::make('Pesanan Proses', $order_processing)
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
            Stat::make('Pesanan Menunggu', $order_pending)
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
            Stat::make('Pesanan Batal', $order_canceled)
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
        ];
    }
}
