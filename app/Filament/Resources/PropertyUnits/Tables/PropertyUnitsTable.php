<?php

namespace App\Filament\Resources\PropertyUnits\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PropertyUnitsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('property.title')
                    ->label('Property / Plaza')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Portion / Shop')
                    ->searchable()
                    ->weight('bold'),
                TextColumn::make('type')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'shop' => 'info',
                        'portion' => 'success',
                        'flat' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('default_rent')
                    ->label('Rent (Rs.)')
                    ->money('PKR')
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'vacant' => 'gray',
                        'occupied' => 'success',
                        default => 'warning',
                    }),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
