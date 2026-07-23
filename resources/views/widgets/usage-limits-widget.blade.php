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
            <div class="fi-fdw-usage">
                @foreach ($limits as $limit)
                    @php
                        $statusColor = $limit->getStatusColor();
                        $limitUrl = $limit->getUrl();
                        $limitTag = filled($limitUrl) ? 'a' : 'div';
                        $warnPercentage = $limit->getWarnPercentage();
                    @endphp

                    <{{ $limitTag }}
                        @if (filled($limitUrl))
                            href="{{ $limitUrl }}"
                            @if ($limit->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
                        @endif
                        style="{{ get_color_css_variables($statusColor, [400, 500, 600]) }}"
                        class="fi-fdw-usage__item"
                    >
                        <div class="fi-fdw-usage__head">
                            <span class="fi-fdw-usage__label">
                                @if ($limit->hasIcon())
                                    <x-filament::icon :icon="$limit->getIcon()" />
                                @endif

                                <span>{{ $limit->getLabel() }}</span>
                            </span>

                            <span class="fi-fdw-usage__values">
                                <span class="fi-fdw-usage__value">{{ $limit->getFormattedValue() }}</span>
                                <span class="fi-fdw-usage__limit">/ {{ $limit->getFormattedLimit() }}</span>
                                <span class="fi-fdw-usage__percentage">{{ $limit->getFormattedPercentage() }}</span>
                            </span>
                        </div>

                        <div
                            class="fi-fdw-bar"
                            role="meter"
                            aria-valuenow="{{ (int) round($limit->getPercentage()) }}"
                            aria-valuemin="0"
                            aria-valuemax="100"
                            aria-label="{{ $limit->getLabel() }}"
                        >
                            @if ($warnPercentage !== null)
                                <span class="fi-fdw-usage__threshold" style="left: {{ $warnPercentage }}%"></span>
                            @endif

                            <div class="fi-fdw-bar__fill" style="width: {{ $limit->getBarWidth() }}%"></div>
                        </div>
                    </{{ $limitTag }}>
                @endforeach
            </div>
        @endunless
    </div>
</x-filament-widgets::widget>
