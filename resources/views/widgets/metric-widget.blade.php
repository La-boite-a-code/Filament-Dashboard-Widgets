@php
    use function Filament\Support\get_color_css_variables;

    $color = $metric->getColor() ?? 'primary';
    $url = $metric->getUrl();
    $tag = filled($url) ? 'a' : 'div';
@endphp

<x-filament-widgets::widget>
    <{{ $tag }}
        @if (filled($url))
            href="{{ $url }}"
            @if ($metric->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
        @endif
        @if (filled($pollingInterval)) wire:poll.{{ $pollingInterval }} @endif
        @if (filled($metric->getTooltip())) title="{{ $metric->getTooltip() }}" @endif
        style="{{ get_color_css_variables($color, [100, 400, 500, 600]) }}"
        @class(['fi-fdw-card', 'fi-fdw-card--interactive' => filled($url)])
    >
        @unless ($hasData)
            <x-filament::empty-state
                :heading="$emptyStateHeading"
                :description="$emptyStateDescription"
                :icon="$emptyStateIcon"
                icon-color="gray"
                :contained="false"
                compact
            />
        @else
            <div class="fi-fdw-metric__header">
                <span class="fi-fdw-label">{{ $metric->getLabel() }}</span>

                @if ($metric->hasIcon())
                    <span class="fi-fdw-metric__icon" aria-hidden="true">
                        <x-filament::icon :icon="$metric->getIcon()" />
                    </span>
                @endif
            </div>

            <div class="fi-fdw-metric__value-row">
                <span class="fi-fdw-metric__value">{{ $metric->getFormattedValue() }}</span>

                @if ($metric->hasTrend())
                    <x-filament::badge
                        :color="$metric->getTrendColor()"
                        :icon="$showTrendIcon ? $metric->getTrendIcon() : null"
                    >
                        {{ $metric->getTrendLabel() }}
                    </x-filament::badge>
                @endif
            </div>

            @if (filled($metric->getDescription()))
                <p class="fi-fdw-description">{{ $metric->getDescription() }}</p>
            @endif

            @if ($metric->hasSparkline())
                <div
                    class="fi-fdw-metric__sparkline"
                    style="height: {{ $sparklineHeight }}px"
                    aria-hidden="true"
                >
                    <svg
                        viewBox="0 0 100 100"
                        preserveAspectRatio="none"
                        style="height: {{ $sparklineHeight }}px"
                        focusable="false"
                    >
                        <polyline
                            class="fi-fdw-metric__sparkline-line"
                            points="{{ $metric->getSparklinePoints($sparklineMaxPoints) }}"
                        />
                    </svg>
                </div>
            @endif
        @endunless
    </{{ $tag }}>
</x-filament-widgets::widget>
