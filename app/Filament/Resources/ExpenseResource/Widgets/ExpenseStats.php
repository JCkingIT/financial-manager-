<?php

namespace App\Filament\Resources\ExpenseResource\Widgets;

use App\Models\Expense;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Number;
use Filament\Widgets\ChartWidget;

class ExpenseStats extends BaseWidget
{

    protected static ?string $pollingInterval = '10s';

    protected function amountDay(): float
    {
        return Expense::where('user_id', Auth::id())
            ->whereDate('created_at', Carbon::now()->toDateString())
            ->sum('amount') / 100;
    }

    protected function amountMonth(): float
    {
        return Expense::where('user_id', Auth::id())
            ->whereMonth('created_at', Carbon::now()->month)
            ->sum('amount') / 100;
    }

    protected function amountWeek(): float
    {
        return Expense::where('user_id', Auth::id())
            ->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->sum('amount') / 100;
    }

    protected function amountYear(): float
    {
        return Expense::where('user_id', Auth::id())
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('amount') / 100;
    }

    protected function getStats(): array
    {
        return [
            Stat::make('Gasto diario', Number::currency($this->amountDay(), 'S/.'))
                ->icon('heroicon-o-clock')
                ->chart($this->getDailyChart())
                ->color('info'),
            Stat::make('Gasto semanal', Number::currency($this->amountWeek(), 'S/.'))
                ->icon('heroicon-o-calendar-days')
                ->chart($this->getWeeklyChart())
                ->color('info'),
            Stat::make('Gasto mensual - ' . Carbon::now()->format('F'), Number::currency($this->amountMonth(), 'S/.'))
                ->icon('heroicon-o-calendar')
                ->chart($this->getMonthlyChart())
                ->color('info'),
            Stat::make('Gasto anual - ' . Carbon::now()->format('Y'), Number::currency($this->amountYear(), 'S/.'))
                ->icon('heroicon-o-chart-bar')
                ->chart($this->getYearlyChart())
                ->color('info'),
        ];
    }

    protected function getDailyChart(): array
    {
        $today = Carbon::now();
        $data = [];

        for ($i = 0; $i < 24; $i++) {
            $amount = Expense::where('user_id', Auth::id())
                ->whereDate('created_at', $today->toDateString())
                ->whereRaw('HOUR(created_at) = ?', [$i])
                ->sum('amount') / 100;
            $data[] = $amount;
        }

        return $data;
    }

    protected function getWeeklyChart(): array
    {
        $data = [];
        $startOfWeek = Carbon::now()->startOfWeek();

        for ($i = 0; $i < 7; $i++) {
            $date = $startOfWeek->copy()->addDays($i);
            $amount = Expense::where('user_id', Auth::id())
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount') / 100;
            $data[] = $amount;
        }

        return $data;
    }

    protected function getMonthlyChart(): array
    {
        $data = [];
        $now = Carbon::now();
        $daysInMonth = $now->daysInMonth;

        for ($i = 1; $i <= $daysInMonth; $i++) {
            $date = Carbon::create($now->year, $now->month, $i);
            $amount = Expense::where('user_id', Auth::id())
                ->whereDate('created_at', $date->toDateString())
                ->sum('amount') / 100;
            $data[] = $amount;
        }

        return $data;
    }

    protected function getYearlyChart(): array
    {
        $data = [];
        $year = Carbon::now()->year;

        for ($i = 1; $i <= 12; $i++) {
            $amount = Expense::where('user_id', Auth::id())
                ->whereYear('created_at', $year)
                ->whereMonth('created_at', $i)
                ->sum('amount') / 100;
            $data[] = $amount;
        }

        return $data;
    }
}
