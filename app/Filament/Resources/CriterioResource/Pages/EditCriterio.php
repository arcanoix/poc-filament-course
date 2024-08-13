<?php

namespace App\Filament\Resources\CriterioResource\Pages;

use App\Filament\Resources\CriterioResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCriterio extends EditRecord
{
    protected static string $resource = CriterioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
