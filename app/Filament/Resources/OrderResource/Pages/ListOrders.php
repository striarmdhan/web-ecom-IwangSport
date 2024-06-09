<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use App\Models\Order;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ExportAction::make('Laporan Order')->url(fn()=> route('download.test'))->openUrlInNewTab(),
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class
        ];
    }

    public function getTabs(): array
    {
        return [
            null => Tab::make('Semua'),
            'baru' => Tab::make()->query(fn ($query) => $query->where('status', 'baru')),
            'diproses' => Tab::make()->query(fn ($query) => $query->where('status', 'diproses')),
            'dikirim' => Tab::make()->query(fn ($query) => $query->where('status', 'dikirim')),
            'terkirim' => Tab::make()->query(fn ($query) => $query->where('status', 'terkirim')),
            'dibatalkan' => Tab::make()->query(fn ($query) => $query->where('status', 'dibatalkan')),
        ];
    }

}
