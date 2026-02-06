<?php

namespace App\Filament\Resources\Governorates\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Parfaitementweb\FilamentCountryField\Forms\Components\Country;

class GovernorateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                ->label('Governorate')
                ->required(),
                Country::make('country')

            ]);
    }
}
