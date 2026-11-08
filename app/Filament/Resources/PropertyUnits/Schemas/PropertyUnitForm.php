<?php

namespace App\Filament\Resources\PropertyUnits\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PropertyUnitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('property_id')
                    ->relationship('property', 'title')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Property / Building'),
                TextInput::make('name')
                    ->required()
                    ->label('Portion / Shop Name')
                    ->placeholder('e.g. Ground Floor Portion, Shop 1'),
                Select::make('type')
                    ->options([
                        'portion' => 'Portion',
                        'shop' => 'Shop',
                        'floor' => 'Floor',
                        'flat' => 'Flat',
                        'office' => 'Office',
                        'warehouse' => 'Warehouse',
                    ])
                    ->default('portion')
                    ->required(),
                TextInput::make('default_rent')
                    ->numeric()
                    ->prefix('Rs.')
                    ->default(0)
                    ->required()
                    ->label('Standard Monthly Rent'),
                Select::make('status')
                    ->options([
                        'vacant' => 'Vacant',
                        'occupied' => 'Occupied',
                    ])
                    ->default('vacant')
                    ->required(),
                Textarea::make('notes')
                    ->columnSpanFull()
                    ->placeholder('Any notes about meters, keys, or condition'),
            ]);
    }
}
