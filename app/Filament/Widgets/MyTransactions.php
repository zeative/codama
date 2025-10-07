<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Number;

class MyTransactions extends ChartWidget
{
    protected ?string $heading = 'My Transactions';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get monthly transaction data with counts and amounts
        $transactionData = DB::table('transactions')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as total_count'),
                DB::raw('SUM(product_amount * product_count) as total_amount'),
                DB::raw('COUNT(CASE WHEN status = "done" THEN 1 END) as completed_count'),
                DB::raw('COUNT(CASE WHEN status = "progress" THEN 1 END) as progress_count'),
                DB::raw('COUNT(CASE WHEN status = "pending" THEN 1 END) as pending_count'),
                DB::raw('COUNT(CASE WHEN status = "cancel" THEN 1 END) as cancelled_count')
            )
            ->where('user_id', auth()->id())
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->get();
        
        // Prepare data for the chart
        $labels = [];
        $totalAmounts = [];
        $completedCounts = [];
        $progressCounts = [];
        $pendingCounts = [];
        
        // For the past 12 months
        $twelveMonthsAgo = Carbon::now()->subMonths(11);
        for ($i = 0; $i < 12; $i++) {
            $month = $twelveMonthsAgo->copy()->addMonths($i)->format('Y-m');
            $labels[] = $twelveMonthsAgo->copy()->addMonths($i)->format('M Y');
            
            $monthData = $transactionData->firstWhere('month', $month);
            
            $totalAmounts[] = $monthData ? (int)$monthData->total_amount : 0;
            $completedCounts[] = $monthData ? (int)$monthData->completed_count : 0;
            $progressCounts[] = $monthData ? (int)$monthData->progress_count : 0;
            $pendingCounts[] = $monthData ? (int)$monthData->pending_count : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Transaction Value',
                    'data' => $totalAmounts,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 2,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Completed Transactions',
                    'data' => $completedCounts,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgb(75, 192, 192)',
                    'borderWidth' => 2,
                    'type' => 'line',
                    'yAxisID' => 'y1',
                ],
                [
                    'label' => 'In Progress',
                    'data' => $progressCounts,
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                    'borderColor' => 'rgb(255, 206, 86)',
                    'borderWidth' => 2,
                    'type' => 'line',
                    'yAxisID' => 'y1',
                ],
                [
                    'label' => 'Pending Transactions',
                    'data' => $pendingCounts,
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgb(153, 102, 255)',
                    'borderWidth' => 2,
                    'type' => 'line',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Transaction Value (IDR)'
                    ],
                    'ticks' => [
                        'callback' => 'function(value) { return "Rp " + value.toLocaleString("id-ID"); }'
                    ]
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Transaction Count'
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
            'plugins' => [
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.dataset.label || "";
                            if (context.parsed.y !== null) {
                                if (context.dataset.label.includes("Value")) {
                                    return label + ": Rp " + context.parsed.y.toLocaleString("id-ID");
                                } else {
                                    return label + ": " + context.parsed.y;
                                }
                            }
                            return label;
                        }'
                    ]
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
