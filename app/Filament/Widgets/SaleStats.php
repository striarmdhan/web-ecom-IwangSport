<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Number;

class SaleStats extends BaseWidget
{   
    protected static bool $isLazy = false;
    protected static ?int $sort = -2;

    protected function getStats(): array {

        $currentGrandTotal = Order::query()->avg('grand_total');

        // Dapatkan rata-rata grand_total sebelumnya dari cache atau database
        $previousGrandTotal = Cache::get('previous_grand_total', 0); // Nilai default adalah 0 jika tidak ada nilai sebelumnya

        // Simpan rata-rata grand_total saat ini ke dalam cache atau database untuk perbandingan di masa mendatang
        Cache::put('previous_grand_total', $currentGrandTotal, now()->addMinutes(60)); // Simpan selama 60 menit atau sesuaikan sesuai kebutuhan

        // Tentukan warna dan ikon berdasarkan perubahan grand_total
        if ($currentGrandTotal > $previousGrandTotal) {
            $color = 'success';
            $icon = 'heroicon-m-arrow-trending-up';
            $description = 'increase';
        } else {
            $color = 'danger';
            $icon = 'heroicon-m-arrow-trending-down';
            $description = 'decrease';
        }
        return [
            Stat::make('Penjualan', Number::currency($currentGrandTotal))
                ->description($description)
                ->descriptionIcon($icon)
                ->color($color),
        ];
    }

}
