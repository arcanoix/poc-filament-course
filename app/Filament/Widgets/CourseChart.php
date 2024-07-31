<?php

namespace App\Filament\Widgets;

use App\Models\Course;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class CourseChart extends ChartWidget
{
    protected static ?string $heading = 'Course Chart';

    protected function getData(): array
    {
        $data = Trend::model(Course::class)
                ->between(
                    start: now()->startOfMonth(),
                    end: now()->endOfMonth(),
                )
                ->perDay()
                ->count();

            return [
                'datasets' => [
                    [
                        'label' => 'Courses records',
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
