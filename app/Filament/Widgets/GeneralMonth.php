<?php

namespace App\Filament\Widgets;

use App\Models\Outcome;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class GeneralMonth extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();

        $income_total = Transaction::query()
            ->where('status', '=', 'done')
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum(DB::raw('product_amount * product_count'));

        $outcome_total = Outcome::query()
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->sum('price');

        $salary_total = $income_total - $outcome_total;

        // Get weekly data for the last 4 weeks for charts
        $fourWeeksAgo = Carbon::now()->subWeeks(3)->startOfWeek();
        $currentWeek = Carbon::now()->endOfWeek();
        
        // Get income weekly data
        $weeklyIncome = Transaction::select(
                DB::raw('SUM(product_amount * product_count) as total'),
                DB::raw('YEARWEEK(created_at, 1) as yearweek')
            )
            ->where('status', '=', 'done')
            ->whereBetween('created_at', [$fourWeeksAgo, $currentWeek])
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy('yearweek', 'asc')
            ->pluck('total')
            ->toArray();
        
        // Fill missing weeks with 0
        $incomeChart = $this->fillMissingWeeks($weeklyIncome, 4);
        
        // Get outcome weekly data
        $weeklyOutcome = Outcome::select(
                DB::raw('SUM(price) as total'),
                DB::raw('YEARWEEK(created_at, 1) as yearweek')
            )
            ->whereBetween('created_at', [$fourWeeksAgo, $currentWeek])
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy('yearweek', 'asc')
            ->pluck('total')
            ->toArray();
        
        // Fill missing weeks with 0
        $outcomeChart = $this->fillMissingWeeks($weeklyOutcome, 4);
        
        // Calculate salary chart (income - outcome per week)
        $salaryChart = [];
        for ($i = 0; $i < 4; $i++) {
            $salaryChart[$i] = isset($incomeChart[$i]) && isset($outcomeChart[$i]) 
                ? $incomeChart[$i] - $outcomeChart[$i] 
                : (isset($incomeChart[$i]) ? $incomeChart[$i] : 0);
        }
        
        // Create a simple chart for "Penggajian"
        $penggajianChart = [];
        for ($i = 0; $i < 4; $i++) {
            $penggajianChart[$i] = 0; // Based on your current implementation
        }

        return [
            Stat::make('Pemasukan', Number::currency($income_total, 'IDR', 'id', 0))
                ->description('Dalam minggu ini')
                ->chart($incomeChart),
            Stat::make('Pengeluaran', Number::currency($outcome_total, 'IDR', 'id', 0))
                ->description('Dalam minggu ini')
                ->chart($outcomeChart),
            Stat::make('Penggajian', Number::currency(0, 'IDR', 'id', 0))
                ->description('Dalam minggu ini')
                ->chart($penggajianChart),
            Stat::make('Pemasukan (kotor)', Number::currency($salary_total, 'IDR', 'id', 0))
                ->description('Dalam minggu ini')
                ->chart($salaryChart),
        ];
    }
    
    /**
     * Fill missing weeks in the data array with 0 values
     */
    private function fillMissingWeeks(array $data, int $totalWeeks): array
    {
        // Ensure we have exactly $totalWeeks values, filling missing weeks with 0
        $result = array_fill(0, $totalWeeks, 0);
        
        $dataCount = count($data);
        $startIndex = max(0, $totalWeeks - $dataCount);
        
        for ($i = 0; $i < min($dataCount, $totalWeeks); $i++) {
            $index = $startIndex + $i;
            $result[$index] = $data[$i] ?? 0;
        }
        
        return $result;
    }
}
