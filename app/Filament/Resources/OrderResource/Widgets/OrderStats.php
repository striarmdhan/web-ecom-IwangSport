<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class OrderStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            // Stat::make('Baru', Order::query()->where('status', 'baru')->count()),
            Stat::make('Diproses', Order::query()->where('status', 'diproses')->count()),
            Stat::make('Dikirim', Order::query()->where('status', 'dikirim')->count()),
            Stat::make('Penjualan', Number::currency(Order::query()->avg('grand_total')))
        ];
    }
}
