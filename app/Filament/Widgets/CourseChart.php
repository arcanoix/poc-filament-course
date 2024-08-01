<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use App\Models\Curso;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CourseChart extends ChartWidget
{
    protected static ?string $heading = 'Grafico Cursos';

    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Trend::model(Curso::class)
                ->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth(),
                )
                ->perDay()
                ->count();

            return [
                'datasets' => [
                    [
                        'label' => 'Cursos Registrados',
                        'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    ],
                ],
                'labels' => $data->map(fn (TrendValue $value) => $value->date),
            ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
