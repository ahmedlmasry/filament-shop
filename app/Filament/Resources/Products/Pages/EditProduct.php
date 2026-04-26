<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $hasVariant = $data['has_variant'] ?? false;
        unset($data['variants']);

        if (!$hasVariant) {
            $this->record?->variants()->delete();
        }
        if (empty($data['has_discount']) || $data['has_discount'] === false) {
            $data['discount'] = null;
            $data['start_discount'] = null;
            $data['end_discount'] = null;
        }
        unset($data['has_discount']);

        return $data;
    }
}