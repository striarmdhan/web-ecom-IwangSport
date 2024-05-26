<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget {

    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(OrderResource::getEloquentQuery())
            ->defaultPaginationPageOption(5)
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->label('Id Pesanan')
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Admin'),

                TextColumn::make('grand_total')
                    ->label('Total')
                    ->money('IDR'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match($state){
                        'baru' => 'info',
                        'diproses' => 'warning',
                        'dikirim' => 'warning',
                        'terkirim' => 'success',
                        'dibatalkan' => 'danger'
                    })
                    ->icon(fn (string $state): string => match($state){
                        'baru' => 'heroicon-m-plus-circle',
                        'diproses' => 'heroicon-m-arrow-path',
                        'dikirim' => 'heroicon-m-truck',
                        'terkirim' => 'heroicon-m-check-badge',
                        'dibatalkan' => 'heroicon-m-x-circle'
                    })
                    ->sortable(),

                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->sortable()
                    ->searchable()
                    ->badge()
                    ->color(fn (string $state): string => match($state){
                        'pending' => 'warning',
                        'terbayar' => 'success',
                        'gagal' => 'danger'
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('Pesanan dibuat')
            ])
            ->actions([
                Action::make('Lihat Pesanan')
                    ->url(fn (Order $record):string => OrderResource::getUrl('view', ['record' => $record]))
                    ->color('info')
                    ->icon('heroicon-o-eye')
            ]);
    }
}
