<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CourseOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Courses', Course::query()->where('is_active', true)->count()),
        ];
    }
}
