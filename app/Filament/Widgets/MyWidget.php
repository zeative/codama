<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Color;
use App\Models\User;
use Carbon\Carbon;
use DB;
use Filament\Widgets\ChartWidget;
use Number;

class MyWidget extends ChartWidget
{
    protected ?string $heading = '📊 Current Trends Analysis';
    protected static ?int $sort = 2;
    protected int|string|array $columnSpan = 'full';

    protected function getData(): array
    {
        // Get top 5 category trends (most transactions in the last 30 days) - for current user
        $categoryTrends = DB::table('transactions')
            ->join('categories', 'transactions.category_id', '=', 'categories.id')
            ->select(
                'categories.name as category_name',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.product_amount * transactions.product_count) as total_revenue')
            )
            ->where('transactions.user_id', auth()->id())
            ->whereBetween('transactions.created_at', [Carbon::now()->subDays(30), Carbon::now()])
            ->groupBy('categories.id', 'categories.name')
            ->orderBy('transaction_count', 'desc')
            ->limit(5)
            ->get();

        // If no data for current user in the last 30 days, get overall top categories
        if ($categoryTrends->isEmpty()) {
            $categoryTrends = DB::table('transactions')
                ->join('categories', 'transactions.category_id', '=', 'categories.id')
                ->select(
                    'categories.name as category_name',
                    DB::raw('COUNT(transactions.id) as transaction_count'),
                    DB::raw('SUM(transactions.product_amount * transactions.product_count) as total_revenue')
                )
                ->where('transactions.user_id', auth()->id())
                ->groupBy('categories.id', 'categories.name')
                ->orderBy('transaction_count', 'desc')
                ->limit(5)
                ->get();
        }

        // Get top 5 color trends (most used in transactions in the last 30 days) - for current user
        $colorTrends = DB::table('transactions')
            ->join('colors', 'transactions.color_id', '=', 'colors.id')
            ->select(
                'colors.name as color_name',
                'colors.merk as color_merk',
                DB::raw('COUNT(transactions.id) as usage_count'),
                DB::raw('SUM(transactions.product_amount * transactions.product_count) as total_revenue')
            )
            ->where('transactions.user_id', auth()->id())
            ->whereBetween('transactions.created_at', [Carbon::now()->subDays(30), Carbon::now()])
            ->groupBy('colors.id', 'colors.name', 'colors.merk')
            ->orderBy('usage_count', 'desc')
            ->limit(5)
            ->get();

        // If no data for current user in the last 30 days, get overall top colors
        if ($colorTrends->isEmpty()) {
            $colorTrends = DB::table('transactions')
                ->join('colors', 'transactions.color_id', '=', 'colors.id')
                ->select(
                    'colors.name as color_name',
                    'colors.merk as color_merk',
                    DB::raw('COUNT(transactions.id) as usage_count'),
                    DB::raw('SUM(transactions.product_amount * transactions.product_count) as total_revenue')
                )
                ->where('transactions.user_id', auth()->id())
                ->groupBy('colors.id', 'colors.name', 'colors.merk')
                ->orderBy('usage_count', 'desc')
                ->limit(5)
                ->get();
        }

        // Get top 5 user trends (most transaction activity in the last 30 days) - for all users
        $userTrends = DB::table('transactions')
            ->join('users', 'transactions.user_id', '=', 'users.id')
            ->select(
                'users.name as user_name',
                DB::raw('COUNT(transactions.id) as transaction_activity'),
                DB::raw('SUM(transactions.product_amount * transactions.product_count) as total_revenue')
            )
            ->whereBetween('transactions.created_at', [Carbon::now()->subDays(30), Carbon::now()])
            ->groupBy('users.id', 'users.name')
            ->orderBy('transaction_activity', 'desc')
            ->limit(5)
            ->get();

        // If no recent data, get overall top users
        if ($userTrends->isEmpty()) {
            $userTrends = DB::table('transactions')
                ->join('users', 'transactions.user_id', '=', 'users.id')
                ->select(
                    'users.name as user_name',
                    DB::raw('COUNT(transactions.id) as transaction_activity'),
                    DB::raw('SUM(transactions.product_amount * transactions.product_count) as total_revenue')
                )
                ->groupBy('users.id', 'users.name')
                ->orderBy('transaction_activity', 'desc')
                ->limit(5)
                ->get();
        }

        // Prepare datasets - using category data as primary dataset
        $categoryNames = $categoryTrends->pluck('category_name')->toArray();
        $transactionCounts = $categoryTrends->pluck('transaction_count')->toArray();
        $revenueData = $categoryTrends->pluck('total_revenue')->toArray();

        // If still no data, set default empty arrays
        if (empty($categoryNames)) {
            $categoryNames = ['No Data Available'];
            $transactionCounts = [0];
            $revenueData = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => 'Category Transaction Count',
                    'data' => $transactionCounts,
                    'backgroundColor' => [
                        'rgba(255, 99, 132, 0.7)',  // Red
                        'rgba(54, 162, 235, 0.7)',  // Blue
                        'rgba(255, 206, 86, 0.7)',  // Yellow
                        'rgba(75, 192, 192, 0.7)',  // Teal
                        'rgba(153, 102, 255, 0.7)', // Purple
                    ],
                    'borderColor' => [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                    ],
                    'borderWidth' => 2,
                    'borderRadius' => 6,
                    'borderSkipped' => false,
                ],
                [
                    'label' => 'Category Revenue (IDR)',
                    'data' => $revenueData,
                    'backgroundColor' => 'rgba(255, 159, 64, 0.5)',
                    'borderColor' => 'rgba(255, 159, 64, 1)',
                    'borderWidth' => 2,
                    'type' => 'line',
                    'order' => 1,
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
            'responsive' => true,
            'maintainAspectRatio' => false,
            'scales' => [
                'x' => [
                    'title' => [
                        'display' => true,
                        'text' => 'Transaction Count',
                        'font' => [
                            'size' => 14,
                            'weight' => 'bold'
                        ]
                    ],
                    'grid' => [
                        'color' => 'rgba(0, 0, 0, 0.1)'
                    ]
                ],
                'y1' => [
                    'position' => 'top',
                    'title' => [
                        'display' => true,
                        'text' => 'Revenue (IDR)',
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
                            if (context.parsed.x !== null) {
                                if (context.dataset.label.includes("Revenue")) {
                                    return label + ": Rp " + context.parsed.x.toLocaleString("id-ID", {
                                        maximumFractionDigits: 0
                                    });
                                } else {
                                    return label + ": " + context.parsed.x + " transactions";
                                }
                            }
                            return label;
                        }',
                        'afterLabel' => 'function(context) {
                            // Additional contextual information could be shown here
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
        return 'bar';
    }
}
