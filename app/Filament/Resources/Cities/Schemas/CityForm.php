<?php

namespace App\Filament\Resources\Cities\Schemas;

use App\Models\Governorate;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class CityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('governorate_id')
                ->label('Governorate')
                ->options(Governorate::all()->pluck('name', 'id'))
                ->searchable()
                ->required(),

            TextInput::make('name')
                ->label('City')
                ->required(),

            TextInput::make('shipping_cost')
                ->label('Shipping Cost')
                ->numeric()
                ->default(0),

            ]);
    }
}
