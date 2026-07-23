<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Widgets;

use LaBoiteACode\FilamentDashboardWidgets\Data\Card;

abstract class CardWidget extends DashboardWidget
{
    /**
     * @var view-string
     */
    protected string $view = 'filament-dashboard-widgets::widgets.card-widget';

    abstract protected function getCard(): Card;

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        $card = $this->getCard();

        return [
            'card' => $card,
            'variant' => $card->getVariant(),
            'pollingInterval' => $this->getPollingInterval(),
        ];
    }
}
