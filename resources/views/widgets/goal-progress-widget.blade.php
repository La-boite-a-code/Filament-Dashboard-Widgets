@php
    use function Filament\Support\get_color_css_variables;

    $color = $goal->getColor() ?? 'primary';
    $url = $goal->getUrl();
    $tag = filled($url) ? 'a' : 'div';
    $status = $goal->getStatus();
    $badgeColor = match ($status) {
        'reached', 'exceeded' => 'success',
        'overdue' => 'danger',
        default => $color,
    };
    $ariaLabel = trans('filament-dashboard-widgets::widgets.goal.progress', [
        'label' => $goal->getLabel(),
        'percentage' => $goal->getFormattedPercentage(),
    ]);
@endphp

<x-filament-widgets::widget>
    <{{ $tag }}
        @if (filled($url))
            href="{{ $url }}"
            @if ($goal->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
        @endif
        @if (filled($pollingInterval)) wire:poll.{{ $pollingInterval }} @endif
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
            <div class="fi-fdw-goal__header">
                <span class="fi-fdw-label">{{ $goal->getLabel() }}</span>

                @if ($goal->hasIcon())
                    <span class="fi-fdw-metric__icon" aria-hidden="true">
                        <x-filament::icon :icon="$goal->getIcon()" />
                    </span>
                @endif
            </div>

            <div class="fi-fdw-goal__values">
                <span>{{ $goal->getFormattedCurrent() }}</span>
                <span class="fi-fdw-goal__target">/ {{ $goal->getFormattedTarget() }}</span>
            </div>

            <div
                @class(['fi-fdw-bar', 'fi-fdw-bar--overflow' => $goal->isExceeded() && $goal->allowsOverflow()])
                role="progressbar"
                aria-valuenow="{{ (int) round($goal->getDisplayPercentage()) }}"
                aria-valuemin="0"
                aria-valuemax="100"
                aria-label="{{ $ariaLabel }}"
            >
                <div class="fi-fdw-bar__fill" style="width: {{ $goal->getBarWidth() }}%"></div>
            </div>

            <div class="fi-fdw-goal__meta">
                <span class="fi-fdw-muted">
                    @if ($status === 'exceeded')
                        {{ trans('filament-dashboard-widgets::widgets.goal.exceeded', ['value' => $goal->getFormattedOverflow()]) }}
                    @elseif ($status === 'reached')
                        {{ trans('filament-dashboard-widgets::widgets.goal.reached') }}
                    @elseif ($goal->shouldShowRemaining())
                        {{ trans('filament-dashboard-widgets::widgets.goal.remaining') }}: {{ $goal->getFormattedRemaining() }}
                    @endif

                    @if ($goal->hasDeadline())
                        &middot; {{ trans('filament-dashboard-widgets::widgets.goal.deadline', ['date' => $goal->getDeadline()->translatedFormat('d M Y')]) }}
                        @if ($goal->isOverdue())
                            ({{ trans('filament-dashboard-widgets::widgets.goal.overdue') }})
                        @endif
                    @endif
                </span>

                @if ($goal->shouldShowPercentage())
                    <x-filament::badge :color="$badgeColor">
                        {{ $goal->getFormattedPercentage() }}
                    </x-filament::badge>
                @endif
            </div>
        @endunless
    </{{ $tag }}>
</x-filament-widgets::widget>
