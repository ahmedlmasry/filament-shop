<?php

namespace App\Filament\Resources\Categories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label(__('auth.name'))
                ->getStateUsing(fn ($record) => $record->getTranslation('name',app()->getLocale()))
                ->searchable(),

            // TextColumn::make('name_ar')
            //     ->label('Name (AR)')
            //     ->getStateUsing(fn ($record) => $record->getTranslation('name', 'ar'))
            //     ->searchable(),

                TextColumn::make('slug')
                    ->searchable(),
                IconColumn::make('status')
                    ->boolean(),
                TextColumn::make('parent.name')
                    ->label('Parent Category')
                    ->sortable(),
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
            ->recordActions([
                EditAction::make(),
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
