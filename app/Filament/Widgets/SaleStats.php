<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class SaleStats extends BaseWidget
{
    protected static ?int $sort = -2;

    protected function getStats(): array {
        return [
            Stat::make('Penjualan', Number::currency(Order::query()->avg('grand_total')))
        ];
    }

}
