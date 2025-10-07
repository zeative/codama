<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use DB;
use Filament\Widgets\ChartWidget;

class TransactionStatusDistribution extends ChartWidget
{
    protected ?string $heading = 'Transaction Status Distribution';
    protected static ?int $sort = 4;
    protected int | string | array $columnSpan = 'full';

    protected function getData(): array
    {
        $userId = auth()->id();

        // Get status distribution data
        $statusData = DB::table('transactions')
            ->select(
                'status',
                DB::raw('COUNT(*) as count')
            )
            ->where('user_id', $userId)
            ->groupBy('status')
            ->get();

        // Define status labels and colors
        $statusLabels = [
            'done' => 'Completed',
            'progress' => 'In Progress',
            'pending' => 'Pending',
            'cancel' => 'Cancelled'
        ];

        $labels = [];
        $data = [];
        $backgroundColors = [];

        foreach ($statusData as $item) {
            $labels[] = $statusLabels[$item->status] ?? ucfirst($item->status);
            $data[] = $item->count;
            
            // Assign colors based on status
            switch ($item->status) {
                case 'done':
                    $backgroundColors[] = 'rgba(75, 192, 192, 0.7)'; // Green
                    break;
                case 'progress':
                    $backgroundColors[] = 'rgba(255, 206, 86, 0.7)'; // Yellow
                    break;
                case 'pending':
                    $backgroundColors[] = 'rgba(54, 162, 235, 0.7)'; // Blue
                    break;
                case 'cancel':
                    $backgroundColors[] = 'rgba(255, 99, 132, 0.7)'; // Red
                    break;
                default:
                    $backgroundColors[] = 'rgba(153, 102, 255, 0.7)'; // Purple
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Transaction Count',
                    'data' => $data,
                    'backgroundColor' => $backgroundColors,
                    'borderColor' => array_map(function($color) {
                        return str_replace('0.7', '1', $color);
                    }, $backgroundColors),
                    'borderWidth' => 1,
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
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => 'function(context) {
                            const label = context.label || "";
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return label + ": " + value + " (" + percentage + "%)";
                        }'
                    ]
                ]
            ]
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}