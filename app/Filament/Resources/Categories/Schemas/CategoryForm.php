<?php

namespace App\Filament\Resources\Categories\Schemas;

use App\Models\Category;
use Filament\Schemas\Components\Section;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Group::make([
                    Tabs::make('Translations')
                    ->tabs([
                        Tabs\Tab::make('English')
                            ->schema([
                                TextInput::make('name.en')
                                    ->label('Name (EN)')
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $operation, $state, Set $set) => $operation === 'create' ? $set('slug', Str::slug($state)) : null)
                                    ->required(),
                                TextInput::make('slug')
                                    ->disabled()
                                    ->unique(Category::class, 'slug', ignoreRecord: true)
                                    ->dehydrated()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $set('slug', Str::slug($state));
                                    }),
                            ]),
                        Tabs\Tab::make('Arabic')
                            ->schema([
                                TextInput::make('name.ar')
                                    ->label('Name (AR)')
                                    ->required(),
                            ]),
                    ]),
                Toggle::make('status')
                    ->label('Active')
                    ->default(true),

                    Select::make('parent_id')
                    ->relationship('parent', 'name', fn (Builder $query) => $query->where('parent_id', null))
                    ->searchable()
                    ->preload()
                    ->placeholder('Select parent category'),
                ])

            ]);
    }
}
