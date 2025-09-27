<?php

namespace App\Filament\Widgets;

use App\Models\Outcome;
use App\Models\Transaction;
use DB;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class GeneralMonth extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $income_total = Transaction::query()
            ->where('status', '=', 'done')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum(DB::raw('product_amount * product_count'));

        $outcome_total = Outcome::query()
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->sum('price');

        $salary_total = $income_total - $outcome_total;

        return [
            Stat::make('Pemasukan', Number::currency($income_total, 'IDR', 'id', 0))
                ->description('Dalam bulan ini'),
            Stat::make('Pengeluaran', Number::currency($outcome_total, 'IDR', 'id', 0))
                ->description('Dalam bulan ini'),
            Stat::make('Penggajian', Number::currency(0, 'IDR', 'id', 0))
                ->description('Dalam bulan ini'),
            Stat::make('Pemasukan (kotor)', Number::currency($salary_total, 'IDR', 'id', 0))
                ->description('Dalam bulan ini')
        ];
    }
}
