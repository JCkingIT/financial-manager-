<?php

namespace App\Filament\Resources\IcomeResource\Pages;

use App\Filament\Resources\IcomeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIcome extends EditRecord
{
    protected static string $resource = IcomeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
