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
        // Change to monthly data while keeping weekly chart
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

        // Get weekly data for the current month for charts
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        // Get income weekly data for the current month
        $weeklyIncome = Transaction::select(
                DB::raw('SUM(product_amount * product_count) as total'),
                DB::raw('YEARWEEK(created_at, 1) as yearweek')
            )
            ->where('status', '=', 'done')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy('yearweek', 'asc')
            ->pluck('total')
            ->toArray();
        
        // Fill missing weeks with 0
        $maxWeeksInMonth = 6; // Max possible weeks in a month
        $incomeChart = $this->fillMissingWeeks($weeklyIncome, $maxWeeksInMonth);
        
        // Get outcome weekly data for the current month
        $weeklyOutcome = Outcome::select(
                DB::raw('SUM(price) as total'),
                DB::raw('YEARWEEK(created_at, 1) as yearweek')
            )
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->groupBy(DB::raw('YEARWEEK(created_at, 1)'))
            ->orderBy('yearweek', 'asc')
            ->pluck('total')
            ->toArray();
        
        // Fill missing weeks with 0
        $outcomeChart = $this->fillMissingWeeks($weeklyOutcome, $maxWeeksInMonth);
        
        // Calculate salary chart (income - outcome per week)
        $salaryChart = [];
        for ($i = 0; $i < $maxWeeksInMonth; $i++) {
            $salaryChart[$i] = isset($incomeChart[$i]) && isset($outcomeChart[$i]) 
                ? $incomeChart[$i] - $outcomeChart[$i] 
                : (isset($incomeChart[$i]) ? $incomeChart[$i] : 0);
        }
        
        // Create a simple chart for "Penggajian"
        $penggajianChart = [];
        for ($i = 0; $i < $maxWeeksInMonth; $i++) {
            $penggajianChart[$i] = 0; // Based on your current implementation
        }

        return [
            Stat::make('Pemasukan', Number::currency($income_total, 'IDR', 'id', 0))
                ->description('Dalam bulan ini')
                ->chart($incomeChart),
            Stat::make('Pengeluaran', Number::currency($outcome_total, 'IDR', 'id', 0))
                ->description('Dalam bulan ini')
                ->chart($outcomeChart),
            Stat::make('Penggajian', Number::currency(0, 'IDR', 'id', 0))
                ->description('Dalam bulan ini')
                ->chart($penggajianChart),
            Stat::make('Pemasukan (kotor)', Number::currency($salary_total, 'IDR', 'id', 0))
                ->description('Dalam bulan ini')
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
