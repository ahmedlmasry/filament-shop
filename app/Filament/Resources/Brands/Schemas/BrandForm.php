<?php

namespace App\Filament\Resources\Brands\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BrandForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('name.en')
                    ->label('Name (EN)')
                    ->required(),
                    TextInput::make('name.ar')
                        ->label('Name (AR)')
                        ->required(),
                    FileUpload::make('logo')
                        ->disk('public')
                        ->directory('brands')
                        ->image()
                        ->required(),
                    Toggle::make('status')
                        ->required(),

                ]),
            ]);
    }
}
