<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Number;
use SebastianBergmann\CodeCoverage\Report\Xml\Totals;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Group::make()->schema([
                    Section::make('Informasi Pesanan')->schema([
                        Select::make('user_id')
                            ->label('Customer')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('payment_method')
                            ->options([
                                'transfer' => 'Transfer',
                                'cod' => 'Cash on delivery'
                            ])
                            ->required(),

                        Select::make('payment_status')
                                ->options([
                                    'pending' => 'Pending',
                                    'terbayar' => 'Terbayar',
                                    'gagal' => 'Gagal'
                                ])
                                ->default('pending')
                                ->required(),
                            
                        Select::make('currency')
                            ->options([
                                'usd' => 'USD',
                                'eur' => 'EUR',
                                'idr' => 'IDR'
                            ])
                            ->required()
                            ->default('idr'),

                        Select::make('shipping_method')
                            ->options([
                                'jnt' => 'JNT',
                                'jne' => 'JNE',
                                'sicepat' => 'SiCepat',
                                'pribadi' => 'Pribadi'
                            ]),
                        
                            ToggleButtons::make('status')
                            ->inline()
                            ->default('baru')
                            ->required()        
                            ->options([
                                'baru' => 'Baru',
                                'diproses' => 'Diproses',
                                'perjalanan' => 'Dalam Perjalanan',
                                'terkirim' => 'Terkirim',
                                'dibatalkan' => 'Dibatalkan'
                            ]) 
                            ->colors([
                                'baru' => 'info',
                                'diproses' => 'warning',
                                'perjalanan' => 'success',
                                'terkirim' => 'success',
                                'dibatalkan' => 'danger'
                            ])
                            ->icons([
                                'baru' => 'heroicon-m-plus-circle',
                                'diproses' => 'heroicon-m-arrow-path',
                                'perjalanan' => 'heroicon-m-truck',
                                'terkirim' => 'heroicon-m-check-badge',
                                'dibatalkan' => 'heroicon-m-x-circle'
                            ]),    

                        Textarea::make('notes')
                            ->columnSpanFull()
    
                    ])->columns(2),
                    // here
                    Section::make('Item Pesanan')->schema([
                        Repeater::make('items')
                        ->relationship()
                        ->schema([

                            Select::make('product_id')
                                ->relationship('product', 'name')
                                ->searchable()
                                ->preload()
                                ->required()
                                ->distinct()
                                ->disableOptionsWhenSelectedInSiblingRepeaterItems()
                                ->reactive()
                                ->afterStateUpdated(fn ($state, Set $set) => $set('unit_amount', Product::find($state) ?->price ?? 0))
                                ->afterStateUpdated(fn ($state, Set $set) => $set('total_amount', Product::find($state) ?->price ?? 0))
                                ->columnSpan(4),

                            TextInput::make('quantity')
                                ->numeric()
                                ->default(1)
                                ->required()
                                ->minValue(1)
                                ->reactive()
                                ->afterStateUpdated(fn ($state, Set $set, Get $get) => $set('total_amount', $state * $get('unit_amount')))
                                ->columnSpan(2),

                            TextInput::make('unit_amount')
                                ->numeric()
                                ->required()
                                ->disabled()
                                ->dehydrated()
                                ->columnSpan(3),

                            TextInput::make('total_amount')
                                ->numeric()
                                ->required()
                                ->dehydrated()
                                ->columnSpan(3),
                                
                        ])->columns(12),

                        Placeholder::make('grand_total_placeholder')
                        ->label('Total Orders')
                        ->content(function (Get $get, Set $set){
                            $total = 0;
                            if (!$repeaters = $get('items')) {
                                return $total;
                            }

                            foreach ($repeaters as $key => $repeaters) {
                                $total += $get("items.{$key}.total_amount");
                            }
                            $set('grand_total', $total);
                            return Number::currency($total, 'IDR');
                        }),
                        
                        // hereee
                        Hidden::make('grand_total')
                            ->default(0)
                    ])

                ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('grand_total')
                    ->label('Total')
                    ->numeric()
                    ->sortable()
                    ->money('IDR'),
                    
                TextColumn::make('payment_method')
                    ->label('Metode')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('payment_status')
                    ->label('Status Pembayaran')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('shipping_method')
                    ->label('Pengiriman')
                    ->sortable()
                    ->searchable(),

                SelectColumn::make('status')
                    ->label('Status Order')
                    ->options([
                        'baru' => 'Baru',
                        'diproses' => 'Diproses',
                        'perjalanan' => 'Dalam Perjalanan',
                        'terkirim' => 'Terkirim',
                        'dibatalkan' => 'Dibatalkan'
                    ])
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make()
                ])
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getNavigationBadge(): ?string {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeColor(): string|array|null {
        return static::getModel()::count() > 10 ? 'danger' : 'orange';
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
