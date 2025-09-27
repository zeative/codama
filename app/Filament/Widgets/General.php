<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class General extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $income_total = Transaction::where('status', '=', 'done')->sum("product_amount");

        return [
            Stat::make('Pemasukan (total)', Number::currency($income_total, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
            Stat::make('Pengeluaran (total)', Number::currency(999999, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
            Stat::make('Penggajian (total)', Number::currency(999999, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
            Stat::make('Pemasukan (kotor)', Number::currency($income_total / 2, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart([700, 200, 1000, 300, 1500, 5000, 9999]),
        ];
    }
}
