<?php

namespace App\Filament\Resources\IcomeResource\Pages;

use App\Filament\Resources\IcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIcomes extends ListRecords
{
    protected static string $resource = IcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
