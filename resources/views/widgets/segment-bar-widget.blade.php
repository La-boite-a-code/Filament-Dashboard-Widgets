@php
    use function Filament\Support\get_color_css_variables;
@endphp

<x-filament-widgets::widget>
    <div
        @if (filled($pollingInterval)) wire:poll.{{ $pollingInterval }} @endif
        class="fi-fdw-card"
    >
        @include('filament-dashboard-widgets::widgets.partials.heading', [
            'heading' => $heading,
            'description' => $headingDescription,
        ])

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
            <div class="fi-fdw-segbar__track" aria-hidden="true">
                @foreach ($segments as $segment)
                    @php $segmentColor = $segment->getColor() ?? 'primary'; @endphp

                    <span
                        class="fi-fdw-segbar__segment"
                        style="{{ get_color_css_variables($segmentColor, [400, 500, 600]) }}; width: {{ $segment->getBarWidth($total) }}%"
                    ></span>
                @endforeach
            </div>

            <div class="fi-fdw-segbar__legend">
                @foreach ($segments as $segment)
                    @php
                        $segmentColor = $segment->getColor() ?? 'primary';
                        $segmentUrl = $segment->getUrl();
                        $segmentTag = filled($segmentUrl) ? 'a' : 'div';
                        $percentage = $segment->getFormattedPercentage($total);
                    @endphp

                    <{{ $segmentTag }}
                        @if (filled($segmentUrl))
                            href="{{ $segmentUrl }}"
                            @if ($segment->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
                        @endif
                        style="{{ get_color_css_variables($segmentColor, [400, 500, 600]) }}"
                        class="fi-fdw-segbar__legend-item"
                    >
                        <span class="fi-fdw-segbar__dot"></span>
                        <span class="fi-fdw-segbar__legend-label">{{ $segment->getLabel() }}</span>
                        <span class="fi-fdw-segbar__legend-value">{{ $segment->getFormattedValue() }}</span>

                        @if (filled($percentage))
                            <span class="fi-fdw-segbar__legend-percentage">{{ $percentage }}</span>
                        @endif
                    </{{ $segmentTag }}>
                @endforeach
            </div>
        @endunless
    </div>
</x-filament-widgets::widget>
