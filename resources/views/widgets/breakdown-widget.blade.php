@php
    use function Filament\Support\get_color_css_variables;

    $viewMoreAction = $this->getAction('viewMore');
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
            <div class="fi-fdw-breakdown__list">
                @foreach ($items as $item)
                    @php
                        $itemColor = $item->getColor() ?? 'primary';
                        $itemUrl = $item->getUrl();
                        $itemTag = filled($itemUrl) ? 'a' : 'div';
                        $percentage = $item->getFormattedPercentage($total);
                    @endphp

                    <{{ $itemTag }}
                        @if (filled($itemUrl))
                            href="{{ $itemUrl }}"
                            @if ($item->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
                        @endif
                        style="{{ get_color_css_variables($itemColor, [400, 500, 600]) }}"
                        class="fi-fdw-breakdown__item"
                    >
                        <div class="fi-fdw-breakdown__row">
                            <span class="fi-fdw-breakdown__label">
                                @if ($item->hasIcon())
                                    <x-filament::icon :icon="$item->getIcon()" />
                                @endif

                                <span>{{ $item->getLabel() }}</span>
                            </span>

                            <span class="fi-fdw-breakdown__values">
                                <span class="fi-fdw-breakdown__value">{{ $item->getFormattedValue() }}</span>

                                @if (filled($percentage))
                                    <span class="fi-fdw-breakdown__percentage">{{ $percentage }}</span>
                                @endif
                            </span>
                        </div>

                        @if ($showBars)
                            <div class="fi-fdw-bar" aria-hidden="true">
                                <div class="fi-fdw-bar__fill" style="width: {{ $item->getBarWidth($total) }}%"></div>
                            </div>
                        @endif
                    </{{ $itemTag }}>
                @endforeach
            </div>

            @if ($viewMoreAction?->isVisible())
                <div class="fi-fdw-footer">
                    {{ $viewMoreAction }}
                </div>
            @endif
        @endunless
    </div>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
