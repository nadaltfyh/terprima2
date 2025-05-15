<?php

namespace App\Filament\Resources\ManageContentResource\Pages;

use App\Filament\Resources\ManageContentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditManageContent extends EditRecord
{
    protected static string $resource = ManageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
