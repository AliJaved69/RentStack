<?php

namespace App\Filament\Resources\Leases\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class LeaseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('property_id')
                    ->required()
                    ->numeric(),
                TextInput::make('tenant_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('start_date')
                    ->required(),
                TextInput::make('base_rent')
                    ->required()
                    ->numeric(),
                TextInput::make('security_deposit_expected')
                    ->required()
                    ->numeric(),
                TextInput::make('security_deposit_paid')
                    ->required()
                    ->numeric(),
                TextInput::make('e_stamp_file_path'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
            ]);
    }
}
