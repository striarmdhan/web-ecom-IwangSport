<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AddressRelationManager extends RelationManager
{
    protected static string $relationship = 'address';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('first_name')
                    ->label('Nama Awal')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('last_name')
                    ->label('Nama Akhir')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('No Telepon')
                    ->required()
                    ->tel()
                    ->maxLength(20),

                TextInput::make('city')
                    ->label('Kota')
                    ->required()
                    ->maxLength(255),
                
                TextInput::make('state')
                    ->label('Negara')
                    ->required()
                    ->maxLength(255),

                TextInput::make('zip_code')
                    ->label('Kode Pos')
                    ->required()
                    ->maxLength(20),

                Textarea::make('street_address')
                    ->label('Alamat Lengkap')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('street_address')
            ->columns([
                TextColumn::make('fullname')
                    ->label('Nama'),
                
                TextColumn::make('phone')
                    ->label('Telepon'),

                TextColumn::make('city')
                    ->label('Kota'),

                TextColumn::make('state')
                    ->label('Negara'),

                TextColumn::make('zip_code')
                    ->label('Kode Pos'),

                TextColumn::make('street_address')
                    ->label('Alamat'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
