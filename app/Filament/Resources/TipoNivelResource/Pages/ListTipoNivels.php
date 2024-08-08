<?php

namespace App\Filament\Resources\TipoNivelResource\Pages;

use App\Filament\Resources\TipoNivelResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTipoNivels extends ListRecords
{
    protected static string $resource = TipoNivelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
