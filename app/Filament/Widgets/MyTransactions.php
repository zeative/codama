<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Filament\Widgets\ChartWidget;
use Number;

class MyTransactions extends ChartWidget
{
    protected ?string $heading = '📊 Transaction Performance Overview';
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
                DB::raw('COUNT(CASE WHEN status = "cancel" THEN 1 END) as cancelled_count'),
                DB::raw('AVG(product_amount * product_count) as average_value')
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
        $averageValues = [];
        
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
            $averageValues[] = $monthData ? round((float)$monthData->average_value) : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Transaction Value',
                    'data' => $totalAmounts,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgb(54, 162, 235)',
                    'borderWidth' => 3,
                    'borderRadius'=> 4,
                    'yAxisID' => 'y',
                    'fill' => false,
                ],
                [
                    'label' => 'Completed Transactions',
                    'data' => $completedCounts,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgb(75, 192, 192)',
                    'borderWidth' => 2,
                    'type' => 'line',
                    'yAxisID' => 'y1',
                    'borderDash' => [5, 5],
                ],
                [
                    'label' => 'In Progress',
                    'data' => $progressCounts,
                    'backgroundColor' => 'rgba(255, 206, 86, 0.2)',
                    'borderColor' => 'rgb(255, 206, 86)',
                    'borderWidth' => 2,
                    'type' => 'line',
                    'yAxisID' => 'y1',
                    'borderDash' => [10, 5],
                ],
                [
                    'label' => 'Pending Transactions',
                    'data' => $pendingCounts,
                    'backgroundColor' => 'rgba(153, 102, 255, 0.2)',
                    'borderColor' => 'rgb(153, 102, 255)',
                    'borderWidth' => 2,
                    'type' => 'line',
                    'yAxisID' => 'y1',
                    'borderDash' => [2, 5],
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Transaction Value (IDR)',
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold'
                        ]
                    ],
                    'ticks' => [
                        'callback' => 'function(value) { 
                            return "Rp " + value.toLocaleString("id-ID", {
                                maximumFractionDigits: 0
                            });
                        }'
                    ],
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)'
                    ]
                ],
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Transaction Count',
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold'
                        ]
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
                'x' => [
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)'
                    ]
                ]
            ],
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                    'labels' => [
                        'usePointStyle' => true,
                        'padding' => 20,
                        'font' => [
                            'size' => 12
                        ]
                    ]
                ],
                'tooltip' => [
                    'enabled' => true,
                    'mode' => 'index',
                    'intersect' => false,
                    'backgroundColor' => 'rgba(0, 0, 0, 0.8)',
                    'titleColor' => '#fff',
                    'bodyColor' => '#fff',
                    'titleFont' => [
                        'size' => 14,
                        'weight' => 'bold'
                    ],
                    'bodyFont' => [
                        'size' => 13
                    ],
                    'padding' => 12,
                    'callbacks' => [
                        'label' => 'function(context) {
                            let label = context.dataset.label || "";
                            if (context.parsed.y !== null) {
                                if (context.dataset.label.includes("Value")) {
                                    return label + ": Rp " + context.parsed.y.toLocaleString("id-ID", {
                                        maximumFractionDigits: 0
                                    });
                                } else {
                                    return label + ": " + context.parsed.y + " transactions";
                                }
                            }
                            return label;
                        }',
                        'afterLabel' => 'function(context) {
                            // This is where we could add additional information
                        }'
                    ]
                ]
            ],
            'animation' => [
                'duration' => 1000,
                'easing' => 'easeOutQuart'
            ]
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
