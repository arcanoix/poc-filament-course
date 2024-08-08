<?php

namespace App\Filament\Widgets;

use App\Models\Curso;
use App\Models\Nivel;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourseOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Cursos', Curso::query()->where('estado', true)->count())->description('Cursos activos en la plataforma')->descriptionIcon('heroicon-o-book-open'),
            Stat::make('Usuarios', User::query()->count())->description('Usuarios registrados en la plataforma')->descriptionIcon('heroicon-o-users'),
            Stat::make('Niveles', Nivel::query()->count())->description('Niveles registrados en la plataforma')->descriptionIcon('heroicon-o-academic-cap'),
        ];
    }
}
