<?php

namespace App\Filament\Resources\Attributes\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AttributeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Attribute Name')
                    ->required()
                    ->maxLength(255),
                Repeater::make('attributeValues')
                    ->relationship('attributeValues')
                    ->schema([
                        TextInput::make('value')
                            ->label('Value')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->grid(2)
                    ->columnSpanFull(),
            ]);
    }
}
