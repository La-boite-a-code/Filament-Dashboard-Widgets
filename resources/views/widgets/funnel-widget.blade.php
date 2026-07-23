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
            <div class="fi-fdw-funnel">
                @foreach ($rows as $row)
                    @php
                        $stage = $row['stage'];
                        $stageColor = $stage->getColor() ?? 'primary';
                        $stageUrl = $stage->getUrl();
                        $stageTag = filled($stageUrl) ? 'a' : 'div';
                    @endphp

                    @unless ($row['isFirst'])
                        <div class="fi-fdw-funnel__connector" aria-hidden="true">
                            <span class="fi-fdw-funnel__connector-line"></span>

                            @if ($row['conversionLabel'] !== null)
                                <span class="fi-fdw-funnel__conversion">
                                    <x-filament::icon icon="heroicon-m-arrow-long-down" />
                                    {{ $row['conversionLabel'] }}
                                </span>
                            @endif
                        </div>
                    @endunless

                    <{{ $stageTag }}
                        @if (filled($stageUrl))
                            href="{{ $stageUrl }}"
                            @if ($stage->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
                        @endif
                        style="{{ get_color_css_variables($stageColor, [400, 500, 600]) }}"
                        class="fi-fdw-funnel__stage"
                    >
                        <div class="fi-fdw-funnel__head">
                            <span class="fi-fdw-funnel__label">
                                @if ($stage->hasIcon())
                                    <x-filament::icon :icon="$stage->getIcon()" />
                                @endif

                                <span>{{ $stage->getLabel() }}</span>
                            </span>

                            <span class="fi-fdw-funnel__value">{{ $stage->getFormattedValue() }}</span>
                        </div>

                        <div class="fi-fdw-funnel__bar-track">
                            <div class="fi-fdw-funnel__bar" style="width: {{ $row['barWidth'] }}%"></div>
                        </div>
                    </{{ $stageTag }}>
                @endforeach
            </div>

            @if ($overallLabel !== null)
                <div class="fi-fdw-footer fi-fdw-muted">
                    {{ trans('filament-dashboard-widgets::widgets.funnel.overall', ['percentage' => $overallLabel]) }}
                </div>
            @endif
        @endunless
    </div>
</x-filament-widgets::widget>
