<?php

namespace App\Filament\Resources\TipoNivelResource\Pages;

use App\Filament\Resources\TipoNivelResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTipoNivel extends EditRecord
{
    protected static string $resource = TipoNivelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
