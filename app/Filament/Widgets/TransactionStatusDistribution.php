<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use DB;
use Filament\Widgets\ChartWidget;

class TransactionStatusDistribution extends ChartWidget
{
    protected ?string $heading = '📋 Transaction Status Distribution';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $userId = auth()->id();

        // Get status distribution data
        $statusData = DB::table('transactions')
            ->select(
                'status',
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(product_amount * product_count) as total_value')
            )
            ->where('user_id', $userId)
            ->groupBy('status')
            ->orderByRaw("FIELD(status, 'done', 'progress', 'pending', 'cancel')")
            ->get();

        // Define status labels and colors
        $statusLabels = [
            'done' => '✅ Completed',
            'progress' => '🔄 In Progress', 
            'pending' => '⏳ Pending',
            'cancel' => '❌ Cancelled'
        ];

        $labels = [];
        $data = [];
        $totalValues = [];
        $backgroundColors = [];
        $borderColors = [];

        foreach ($statusData as $item) {
            $label = $statusLabels[$item->status] ?? ucfirst($item->status);
            $labels[] = $label;
            $data[] = $item->count;
            $totalValues[] = $item->total_value;
            
            // Assign colors based on status
            switch ($item->status) {
                case 'done':
                    $backgroundColors[] = 'rgba(75, 192, 192, 0.7)'; // Green
                    $borderColors[] = 'rgb(75, 192, 192)';
                    break;
                case 'progress':
                    $backgroundColors[] = 'rgba(255, 206, 86, 0.7)'; // Yellow
                    $borderColors[] = 'rgb(255, 206, 86)';
                    break;
                case 'pending':
                    $backgroundColors[] = 'rgba(54, 162, 235, 0.7)'; // Blue
                    $borderColors[] = 'rgb(54, 162, 235)';
                    break;
                case 'cancel':
                    $backgroundColors[] = 'rgba(255, 99, 132, 0.7)'; // Red
                    $borderColors[] = 'rgb(255, 99, 132)';
                    break;
                default:
                    $backgroundColors[] = 'rgba(153, 102, 255, 0.7)'; // Purple
                    $borderColors[] = 'rgb(153, 102, 255)';
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Number of Transactions',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => $borderColors,
                    'borderWidth' => 2,
                    'hoverOffset' => 4,
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
            'cutout' => '65%', // Makes it a doughnut instead of pie
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                    'labels' => [
                        'padding' => 20,
                        'usePointStyle' => true,
                        'font' => [
                            'size' => 12
                        ]
                    ]
                ],
                'tooltip' => [
                    'enabled' => true,
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
                            const label = context.label || "";
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ": " + value + " transactions (" + percentage + "%)";
                        }',
                        'afterLabel' => 'function(context) {
                            // Here we could add more detailed information if needed
                        }'
                    ]
                ]
            ],
            'animation' => [
                'animateRotate' => true,
                'animateScale' => false,
                'duration' => 1000,
                'easing' => 'easeOutQuart'
            ]
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}