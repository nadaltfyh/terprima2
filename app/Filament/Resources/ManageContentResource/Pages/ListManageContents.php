<?php

namespace App\Filament\Resources\ManageContentResource\Pages;

use App\Filament\Resources\ManageContentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListManageContents extends ListRecords
{
    protected static string $resource = ManageContentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
