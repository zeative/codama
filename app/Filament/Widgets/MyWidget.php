<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\Category;
use Carbon\Carbon;
use DB;
use Filament\Widgets\ChartWidget;
use Number;

class MyWidget extends ChartWidget
{
    protected ?string $heading = 'My Transaction Analytics';
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get transaction data grouped by category
        $categoryData = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(transactions.product_amount * transactions.product_count) as total_amount'),
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('AVG(transactions.product_amount * transactions.product_count) as avg_transaction_value')
            )
            ->where('transactions.user_id', auth()->id())
            ->where('transactions.status', 'done')
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('total_amount', 'desc')
            ->limit(5)
            ->get();

        // Prepare data for charts
        $categoryNames = $categoryData->pluck('category_name')->toArray();
        $totalAmounts = $categoryData->pluck('total_amount')->toArray();
        $transactionCounts = $categoryData->pluck('transaction_count')->toArray();
        $avgValues = $categoryData->pluck('avg_transaction_value')->toArray();

        // Get monthly trend data
        $monthlyData = DB::table('transactions')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('SUM(product_amount * product_count) as total_amount'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->where('user_id', auth()->id())
            ->where('status', 'done')
            ->whereBetween('created_at', [Carbon::now()->subMonths(5)->startOfMonth(), Carbon::now()])
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy('month')
            ->get();

        $months = [];
        $monthlyAmounts = [];
        $monthlyCounts = [];

        $fiveMonthsAgo = Carbon::now()->subMonths(5);
        for ($i = 0; $i < 6; $i++) {
            $month = $fiveMonthsAgo->copy()->addMonths($i)->format('Y-m');
            $monthDisplay = $fiveMonthsAgo->copy()->addMonths($i)->format('M');
            $months[] = $monthDisplay;

            $monthlyRecord = $monthlyData->firstWhere('month', $month);
            $monthlyAmounts[] = $monthlyRecord ? (int)$monthlyRecord->total_amount : 0;
            $monthlyCounts[] = $monthlyRecord ? (int)$monthlyRecord->transaction_count : 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Total Revenue by Category (IDR)',
                    'data' => $totalAmounts,
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(153, 102, 255, 0.7)',
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    'borderWidth' => 1,
                    'yAxisID' => 'y',
                ],
                [
                    'label' => 'Transaction Count',
                    'data' => array_slice($monthlyCounts, -max(count($totalAmounts), 1)), // Adjust to match categories if needed
                    'backgroundColor' => 'rgba(255, 159, 64, 0.7)',
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'borderWidth' => 1,
                    'type' => 'bar',
                    'yAxisID' => 'y1',
                ]
            ],
            'labels' => $categoryNames,
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Amount (IDR)'
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
                                if (context.dataset.label.includes("Revenue")) {
                                    return label + ": Rp " + context.parsed.x.toLocaleString("id-ID");
                                } else {
                                    return label + ": " + context.parsed.x;
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
        return 'bar';
    }
}
