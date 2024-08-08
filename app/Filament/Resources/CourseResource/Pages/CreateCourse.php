<?php

namespace App\Filament\Resources\CourseResource\Pages;

use App\Filament\Resources\CourseResource;
use App\Jobs\UpdateSync;
use App\Models\Curso;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Log;

class CreateCourse extends CreateRecord
{
    protected static string $resource = CourseResource::class;

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Curso registrado con exito';
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Curso
    {
        $process = static::getModel()::create($data);

        // Dispatch the job to update the sync
        UpdateSync::dispatch($process);

        return $process;
    }





}
