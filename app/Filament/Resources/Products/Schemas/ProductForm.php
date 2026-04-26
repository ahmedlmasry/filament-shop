<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use App\Models\Attribute;
use App\Models\AttributeValue;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // ─── Basic Information ────────────────────────────────────────
                Section::make('Basic Information')
                    ->description('Enter the basic product details')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name.en')
                            ->label('Name (EN)')
                            ->required(),

                        TextInput::make('name.ar')
                            ->label('Name (AR)')
                            ->required(),

                        TextInput::make('small_desc.en')
                            ->label('Small Description (EN)')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('small_desc.ar')
                            ->label('Small Description (AR)')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('desc.en')
                            ->label('Full Description (EN)')
                            ->required(),

                        TextInput::make('desc.ar')
                            ->label('Full Description (AR)')
                            ->required(),

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
                                    ->imagePreviewHeight('150')
                                    ->columnSpanFull(),
                            ])
                            ->minItems(1)
                            ->maxItems(10)
                            ->collapsible()
                            ->itemLabel(fn(?array $state): ?string => !empty($state['file_name'] ?? null) ? 'Image' : 'New Image')
                            ->reorderable()
                            ->columnSpanFull()
                            ->helperText('Upload product images. First image will be the main product image.'),
                    ]),

                // ─── Pricing & Inventory ──────────────────────────────────────
                Section::make('Pricing & Inventory')
                    ->description('Set pricing, stock, and discount information')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price')
                            ->label('Price')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->disabled(fn(Get $get) => $get('has_variant') === true),

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

                        Toggle::make('has_discount')
                            ->label('Has Discount')
                            ->live()
                            ->dehydrated(true)
                            ->afterStateHydrated(function ($component, $state, $record) {
                                $component->state(
                                    !is_null($record?->discount)
                                );
                            })
                            ->columnSpanFull(),

                        TextInput::make('discount')
                            ->label('Discount Price')
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->visible(fn(Get $get) => $get('has_discount') === true)
                            ->required(fn(Get $get) => $get('has_discount') === true)
                            ->dehydrated(true),

                        DateTimePicker::make('start_discount')
                            ->label('Discount Start Date')
                            ->visible(fn(Get $get) => $get('has_discount') === true)
                            ->required(fn(Get $get) => $get('has_discount') === true)
                            ->dehydrated(true),

                        DateTimePicker::make('end_discount')
                            ->label('Discount End Date')
                            ->visible(fn(Get $get) => $get('has_discount') === true)
                            ->required(fn(Get $get) => $get('has_discount') === true)
                            ->after('start_discount')
                            ->dehydrated(true),
                    ]),

                // ─── Variants & Availability ──────────────────────────────────
                Section::make('Variants & Availability')
                    ->description('Configure product variants and availability')
                    ->columns(2)
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
                            ->columnSpanFull(),
                        Repeater::make('variants')
                            ->relationship('variants')
                            ->dehydrated(false)
                            ->visible(fn(Get $get) => $get('has_variant'))
                            ->required(fn(Get $get) => $get('has_variant'))
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

                                Repeater::make('variantAttributes')
                                    ->relationship('variantAttributes')
                                    ->schema([
                                        Select::make('attribute_id')
                                            ->label('Attribute')
                                            ->options(Attribute::pluck('name', 'id'))
                                            ->dehydrated(false)
                                            ->live()
                                            ->afterStateHydrated(function ($component, $state, Get $get) {
                                                if (!$state && $valueId = $get('attribute_value_id')) {
                                                    $attributeValue = AttributeValue::find($valueId);
                                                    if ($attributeValue) {
                                                        $component->state($attributeValue->attribute_id);
                                                    }
                                                }
                                            })
                                            ->required(),

                                        Select::make('attribute_value_id')
                                            ->label('Value')
                                            ->options(function (Get $get) {
                                                return AttributeValue::where('attribute_id', $get('attribute_id'))
                                                    ->pluck('value', 'id');
                                            })
                                            ->required(),
                                    ])
                                    ->minItems(1)
                                    ->columns(2),
                            ])
                            ->minItems(1)
                            ->maxItems(10)
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn(?array $state): ?string => isset($state['price']) ? (string) $state['price'] : null)
                            ->required(fn(Get $get) => $get('has_variant') === true)
                            ->columnSpanFull(),
                    ]),

            ]);
    }
}