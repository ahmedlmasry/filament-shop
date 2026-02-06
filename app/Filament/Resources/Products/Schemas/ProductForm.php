<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Wizard;
use Filament\Schemas\Components\Wizard\Step;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Wizard::make([
                    Step::make('Basic Information')
                        ->description('Enter the basic product details')
                        ->schema([
                            TextInput::make('name')
                                ->label('Product Name')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            TextInput::make('small_desc')
                                ->label('Small Description')
                                ->required()
                                ->maxLength(255)
                                ->columnSpanFull(),

                            TextInput::make('desc')
                                ->label('Full Description')
                                ->required()
                                ->columnSpanFull(),

                            Select::make('category_id')
                                ->label('Category')
                                ->relationship('category', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),

                            Select::make('brand_id')
                                ->label('Brand')
                                ->relationship('brand', 'name')
                                ->required()
                                ->searchable()
                                ->preload(),

                            Select::make('tags')
                                ->label('Tags')
                                ->relationship('tags', 'name')
                                ->multiple()
                                ->searchable()
                                ->preload()
                                ->required()
                                ->columnSpanFull(),

                            Repeater::make('images')
                                ->label('Product Images')
                                ->relationship('images')
                                ->schema([
                                    FileUpload::make('file_name')
                                        ->label('Image')
                                        ->image()
                                        ->required()
                                        ->disk('public')
                                        ->directory('products')
                                        ->imageEditor()
                                        ->maxSize(2048)
                                        ->columnSpanFull(),
                                ])
                                ->minItems(1)
                                ->maxItems(10)
                                ->collapsible()
                                ->itemLabel(fn(array $state): ?string => $state['file_name'] ? 'Image' : 'New Image')
                                ->reorderable()
                                ->columnSpanFull()
                                ->helperText('Upload product images. First image will be the main product image.'),
                        ])
                        ->columns(2),

                    Step::make('Pricing & Inventory')
                        ->description('Set pricing, stock, and discount information')
                        ->schema([
                            TextInput::make('price')
                                ->label('Price')
                                ->required()
                                ->numeric()
                                ->prefix('$')
                                ->minValue(0),

                            TextInput::make('sku')
                                ->label('SKU')
                                ->required()
                                ->unique(ignoreRecord: true)
                                ->maxLength(255),

                            Toggle::make('manage_stock')
                                ->label('Manage Stock')
                                ->live()
                                ->columnSpanFull(),

                            TextInput::make('quantity')
                                ->label('Stock Quantity')
                                ->numeric()
                                ->minValue(0)
                                ->visible(fn(Get $get) => $get('manage_stock') === true)
                                ->required(fn(Get $get) => $get('manage_stock') === true),

                            TextInput::make('views')
                                ->label('Views')
                                ->numeric()
                                ->default(0)
                                ->minValue(0),

                            Toggle::make('has_discount')
                                ->label('Has Discount')
                                ->live()
                                ->dehydrated(false)
                                ->columnSpanFull(),

                            TextInput::make('discount')
                                ->label('Discount Price')
                                ->numeric()
                                ->prefix('$')
                                ->minValue(0)
                                ->visible(fn(Get $get) => $get('has_discount') === true)
                                ->required(fn(Get $get) => $get('has_discount') === true),

                            DateTimePicker::make('start_discount')
                                ->label('Discount Start Date')
                                ->visible(fn(Get $get) => $get('has_discount') === true)
                                ->required(fn(Get $get) => $get('has_discount') === true),

                            DateTimePicker::make('end_discount')
                                ->label('Discount End Date')
                                ->visible(fn(Get $get) => $get('has_discount') === true)
                                ->required(fn(Get $get) => $get('has_discount') === true)
                                ->after('start_discount'),
                        ])
                        ->columns(2),

                    Step::make('Variants & Availability')
                        ->description('Configure product variants and availability')
                        ->schema([
                            Toggle::make('status')
                                ->label('Active Status')
                                ->default(true)
                                ->inline(false),

                            DateTimePicker::make('available_for')
                                ->label('Available From')
                                ->required()
                                ->default(now()),

                            Toggle::make('has_variant')
                                ->label('Has Variants')
                                ->live()
                                ->dehydrated(false)
                                ->columnSpanFull(),

                            Repeater::make('variants')
                                ->label('Product Variants')
                                ->relationship('variants')
                                ->schema([
                                    TextInput::make('price')
                                        ->label('Variant Price')
                                        ->required()
                                        ->numeric()
                                        ->prefix('$')
                                        ->minValue(0),

                                    TextInput::make('stock')
                                        ->label('Stock Quantity')
                                        ->required()
                                        ->numeric()
                                        ->minValue(0),

                                    FileUpload::make('image')
                                        ->label('Variant Image')
                                        ->image()
                                        ->required()
                                        ->disk('public')
                                        ->directory('product-variants'),
                                ])
                                ->minItems(1)
                                ->maxItems(10)
                                ->collapsible()
                                ->collapsed()
                                ->itemLabel(fn(array $state): ?string => $state['price'] ?? null)
                                ->visible(fn(Get $get) => $get('has_variant') === true)
                                ->required(fn(Get $get) => $get('has_variant') === true)
                                ->columnSpanFull(),
                        ])
                        ->columns(2),
                ])
                    ->columnSpanFull()
                    ->persistStepInQueryString()
            ]);
    }
}
