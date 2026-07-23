@php
    use function Filament\Support\get_color_css_variables;

    $color = $bullet->getColor() ?? 'primary';
    $url = $bullet->getUrl();
    $tag = filled($url) ? 'a' : 'div';
    $status = $bullet->getStatus();

    $ariaValueText = $bullet->getFormattedValue();

    if ($bullet->getFormattedTarget() !== null) {
        $ariaValueText .= ', '.trans('filament-dashboard-widgets::widgets.bullet.target', [
            'value' => $bullet->getFormattedTarget(),
        ]);
    }

    if ($status !== 'none') {
        $ariaValueText .= ', '.trans('filament-dashboard-widgets::widgets.bullet.status.'.$status);
    }
@endphp

<x-filament-widgets::widget>
    <{{ $tag }}
        @if (filled($url))
            href="{{ $url }}"
            @if ($bullet->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
        @endif
        @if (filled($pollingInterval)) wire:poll.{{ $pollingInterval }} @endif
        style="{{ get_color_css_variables($color, [400, 500, 600]) }}"
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
            <div class="fi-fdw-bullet">
                <div class="fi-fdw-bullet__header">
                    <div>
                        <div class="fi-fdw-label">{{ $bullet->getLabel() }}</div>
                        <div class="fi-fdw-bullet__value">{{ $bullet->getFormattedValue() }}</div>
                    </div>

                    @if ($status !== 'none')
                        <x-filament::badge :color="$bullet->getStatusColor()">
                            {{ trans('filament-dashboard-widgets::widgets.bullet.status.'.$status) }}
                        </x-filament::badge>
                    @endif
                </div>

                <div
                    class="fi-fdw-bullet__track"
                    role="meter"
                    aria-valuenow="{{ round($bullet->getValue(), 2) }}"
                    aria-valuemin="0"
                    aria-valuemax="{{ round($bullet->getScaleMax(), 2) }}"
                    aria-label="{{ $bullet->getLabel() }}"
                    aria-valuetext="{{ $ariaValueText }}"
                >
                    @foreach ($bullet->getBands() as $band)
                        <div
                            class="fi-fdw-bullet__band fi-fdw-bullet__band--{{ $band['shade'] }}"
                            style="width: {{ $band['width'] }}%"
                        ></div>
                    @endforeach

                    <div class="fi-fdw-bullet__measure" style="width: {{ $bullet->getValuePercentage() }}%"></div>

                    @if ($bullet->getTargetPercentage() !== null)
                        <div class="fi-fdw-bullet__target" style="left: {{ $bullet->getTargetPercentage() }}%"></div>
                    @endif

                    @if ($bullet->getComparativePercentage() !== null)
                        <div class="fi-fdw-bullet__marker" style="left: {{ $bullet->getComparativePercentage() }}%"></div>
                    @endif
                </div>

                @if ($bullet->getFormattedTarget() !== null || $bullet->getComparative() !== null || filled($bullet->getDescription()))
                    <div class="fi-fdw-bullet__caption fi-fdw-muted">
                        @if ($bullet->getFormattedTarget() !== null)
                            <span>{{ trans('filament-dashboard-widgets::widgets.bullet.target', ['value' => $bullet->getFormattedTarget()]) }}</span>
                        @endif

                        @if ($bullet->getComparative() !== null)
                            <span>{{ trans('filament-dashboard-widgets::widgets.bullet.benchmark', ['value' => $bullet->getFormattedComparative()]) }}</span>
                        @endif

                        @if (filled($bullet->getDescription()))
                            <span>{{ $bullet->getDescription() }}</span>
                        @endif
                    </div>
                @endif
            </div>
        @endunless
    </{{ $tag }}>
</x-filament-widgets::widget>
