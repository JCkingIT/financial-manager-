<?php

namespace App\Filament\Widgets\Chart;

use App\Models\Expense;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class ExpenseChart extends ChartWidget
{
    protected static ?string $heading = 'Gastos';
    protected static ?int $sort = 1;
    protected static bool $shouldPollToRefresh = false;

    protected function getData(): array
    {
        $userId = Auth::id();

        $gastos = Expense::where('user_id', $userId)
            ->whereYear('created_at', Carbon::now()->year)
            ->selectRaw('MONTH(created_at) as mes, SUM(amount) as total')
            ->groupBy('mes')
            ->pluck('total', 'mes')
            ->toArray();

        $labels = collect(range(1, 12))
            ->map(fn($month) => Carbon::create()->month($month)->format('M'))
            ->toArray();

        $data = collect(range(1, 12))
            ->map(fn($month) => isset($gastos[$month]) ? $gastos[$month] / 100 : 0)
            ->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Gastos en USD',
                    'data' => $data,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.5)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 2,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
