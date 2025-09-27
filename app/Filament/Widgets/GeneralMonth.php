<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class GeneralMonth extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pemasukan (bulan)', Number::currency(657658, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
            Stat::make('Pengeluaran (bulan)', Number::currency(56546, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
            Stat::make('Penggajian (bulan)', Number::currency(56667, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
            Stat::make('Pemasukan (kotor)', Number::currency(54686, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
        ];
    }
}
