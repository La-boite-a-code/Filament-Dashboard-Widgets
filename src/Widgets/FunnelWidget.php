<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use Illuminate\Support\Number;
use LaBoiteACode\FilamentDashboardWidgets\Concerns\HasHeading;
use LaBoiteACode\FilamentDashboardWidgets\Data\FunnelStage;

abstract class FunnelWidget extends DashboardWidget
{
    use HasHeading;

    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.funnel-widget';

    /**
     * @return array<int, FunnelStage>
     */
    abstract protected function getStages(): array;

    /**
     * @return array<int, FunnelStage>
     */
    protected function resolveStages(): array
    {
        return array_values(array_filter(
            $this->getStages(),
            static fn (mixed $stage): bool => $stage instanceof FunnelStage,
        ));
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $stages = $this->resolveStages();
        $topValue = $stages === [] ? 0.0 : $stages[0]->getValue();

        $rows = [];
        $previous = null;

        foreach ($stages as $index => $stage) {
            $value = $stage->getValue();
            $conversion = ($previous !== null && $previous > 0.0) ? ($value / $previous) * 100 : null;

            $rows[] = [
                'stage' => $stage,
                'barWidth' => $stage->getBarWidth($topValue),
                'conversionLabel' => $conversion === null
                    ? null
                    : (string) Number::percentage($conversion, maxPrecision: 1),
                'isFirst' => $index === 0,
            ];

            $previous = $value;
        }

        $overallLabel = null;

        if (count($stages) > 1 && $topValue > 0.0) {
            $overall = ($stages[array_key_last($stages)]->getValue() / $topValue) * 100;
            $overallLabel = (string) Number::percentage($overall, maxPrecision: 1);
        }

        return [
            'rows' => $rows,
            'hasData' => $stages !== [],
            'overallLabel' => $overallLabel,
            'heading' => $this->getHeading(),
            'headingDescription' => $this->getHeadingDescription(),
            'pollingInterval' => $this->getPollingInterval(),
            'emptyStateHeading' => $this->getEmptyStateHeading(),
            'emptyStateDescription' => $this->getEmptyStateDescription(),
            'emptyStateIcon' => $this->getEmptyStateIcon(),
        ];
    }
}
