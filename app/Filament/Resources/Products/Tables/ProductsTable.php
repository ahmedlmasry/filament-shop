<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;



class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // 🖼️ Image
                ImageColumn::make('images.file_name')
                    ->label('Image')
                    ->disk('public')
                    ->square()
                    ->defaultImageUrl(url('/images/placeholder.png')),

                // 📝 Name
                TextColumn::make('name')
                    ->label('Name')
                    ->formatStateUsing(fn($record) => $record->getTranslation('name', 'en'))
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),

                // 🏷️ SKU
                TextColumn::make('sku')
                    ->label('SKU')
                    ->copyable()
                    ->copyMessage('Copied!')
                    ->color('gray')
                    ->searchable(),

                // 📂 Category
                TextColumn::make('category.name')
                    ->label('Category')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                // 🏢 Brand
                TextColumn::make('brand.name')
                    ->label('Brand')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                // 💰 Price
                TextColumn::make('price')
                    ->label('Price')
                    ->placeholder('N/A')
                    ->money('USD')
                    ->sortable()
                    ->color('success'),

                // 🔥 Discount
                TextColumn::make('discount')
                    ->label('Discount')
                    ->money('USD')
                    ->color('danger')
                    ->visible(fn($record) => filled($record?->discount)),

                // 💸 Final Price
                TextColumn::make('final_price')
                    ->label('Final Price')
                    ->state(fn($record) => $record ? ($record->discount ?? $record->price) : 0)
                    ->money('USD')
                    ->color('primary')
                    ->weight('bold'),

                // 📦 Stock
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->colors([
                        'danger' => fn($state) => $state == 0,
                        'warning' => fn($state) => $state < 10,
                        'success' => fn($state) => $state >= 10,
                    ]),

                // ⚙️ Manage Stock
                IconColumn::make('manage_stock')
                    ->label('Stock')
                    ->boolean(),

                // 🔵 Status
                TextColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => fn($state) => $state,
                        'danger' => fn($state) => !$state,
                    ])
                    ->formatStateUsing(fn($state) => $state ? 'Active' : 'Inactive'),

                // 👁️ Views
                TextColumn::make('views')
                    ->label('Views')
                    ->numeric()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 📅 Created
                TextColumn::make('created_at')
                    ->label('Created')
                    ->since()
                    ->sortable()
                    ->toggleable(),
            ])

            ->defaultSort('created_at', 'desc')

            ->striped()
            ->paginated([10, 25, 50])

            ->filters([
                //
            ])

            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    EditAction::make(),
                    DeleteAction::make(),
                ])
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

