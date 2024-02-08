<?php

namespace App\Filament\Resources\UserResource\Widgets;

use App\Models\User;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Leandrocfe\FilamentApexCharts\Widgets\ApexChartWidget;

class UsersChart extends ApexChartWidget
{
    /**
     * Chart Id
     *
     * @var string
     */
    protected static ?string $chartId = 'usersChart';

    /**
     * Widget Title
     *
     * @var string|null
     */
    protected static ?string $heading = 'UsersChart';

    /**
     * Chart options (series, labels, types, size, animations...)
     * https://apexcharts.com/docs/options
     *
     * @return array
     */
    protected function getOptions(): array
    {
        $thisYear = Trend::model(User::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        $lastYear = Trend::model(User::class)
            ->between(
                start: now()->startOfYear()->subYears(1),
                end: now()->endOfYear()->subYears(1),
            )
            ->perMonth()
            ->count();

        return [
            'chart' => [
                'type' => 'line',
                'height' => 300,
            ],
            'series' => [

                [
                    'name' => 'Users This Year',
                    'data' => $thisYear->map(fn (TrendValue $value) => $value->aggregate),
                ],

                [
                    'name' => 'Users Last Year',
                    'data' => $lastYear->map(fn (TrendValue $value) => $value->aggregate),

                ],


            ],
            'xaxis' => [
                'categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'yaxis' => [
                'labels' => [
                    'style' => [
                        'fontFamily' => 'inherit',
                    ],
                ],
            ],
            'colors' => ['#f59e0b','#FF0000'],
            'stroke' => [
                'curve' => 'smooth',
            ],

        ];
    }
}
