<?php

namespace App\Filament\Widgets;

use App\Models\Outcome;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Number;

class General extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        // Get monthly data for the last 12 months for charts
        $twelveMonthsAgo = Carbon::now()->subMonths(11)->startOfMonth();
        $currentMonth = Carbon::now()->endOfMonth();
        
        // Get income monthly data
        $monthlyIncome = Transaction::select(
                DB::raw('SUM(product_amount * product_count) as total'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month')
            )
            ->where('status', '=', 'done')
            ->whereBetween('created_at', [$twelveMonthsAgo, $currentMonth])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->pluck('total')
            ->toArray();
        
        // Fill missing months with 0
        $incomeChart = $this->fillMissingMonths($monthlyIncome, 12);
        
        // Get outcome monthly data
        $monthlyOutcome = Outcome::select(
                DB::raw('SUM(price) as total'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month')
            )
            ->whereBetween('created_at', [$twelveMonthsAgo, $currentMonth])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->pluck('total')
            ->toArray();
        
        // Fill missing months with 0
        $outcomeChart = $this->fillMissingMonths($monthlyOutcome, 12);
        
        // Calculate total values
        $income_total = Transaction::where('status', '=', 'done')->sum(DB::raw('product_amount * product_count')); 
        $outcome_total = Outcome::sum("price");
        $salary_total = $income_total - $outcome_total;
        
        // Calculate salary chart (income - outcome per month)
        $salaryChart = [];
        for ($i = 0; $i < 12; $i++) {
            $salaryChart[$i] = isset($incomeChart[$i]) && isset($outcomeChart[$i]) 
                ? $incomeChart[$i] - $outcomeChart[$i] 
                : (isset($incomeChart[$i]) ? $incomeChart[$i] : 0);
        }
        
        // Create a simple average-based chart for "Penggajian" since it's currently hardcoded to 0
        $penggajianChart = [];
        for ($i = 0; $i < 12; $i++) {
            $penggajianChart[$i] = 0; // Based on your current implementation
        }

        return [
            Stat::make('Pemasukan', Number::currency($income_total, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart($incomeChart),
            Stat::make('Pengeluaran', Number::currency($outcome_total, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart($outcomeChart),
            Stat::make('Penggajian', Number::currency(0, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart($penggajianChart),
            Stat::make('Pemasukan (kotor)', Number::currency($salary_total, 'IDR', 'id', 0))
                ->description('Semua periode')
                ->chart($salaryChart),
        ];
    }
    
    /**
     * Fill missing months in the data array with 0 values
     */
    private function fillMissingMonths(array $data, int $totalMonths): array
    {
        // Ensure we have exactly $totalMonths values, filling missing months with 0
        $result = array_fill(0, $totalMonths, 0);
        
        $dataCount = count($data);
        $startIndex = max(0, $totalMonths - $dataCount);
        
        for ($i = 0; $i < min($dataCount, $totalMonths); $i++) {
            $index = $startIndex + $i;
            $result[$index] = $data[$i] ?? 0;
        }
        
        return $result;
    }
}
