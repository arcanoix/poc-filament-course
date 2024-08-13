<?php

namespace App\Filament\Resources\CriterioResource\Pages;

use App\Filament\Resources\CriterioResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCriterios extends ListRecords
{
    protected static string $resource = CriterioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
