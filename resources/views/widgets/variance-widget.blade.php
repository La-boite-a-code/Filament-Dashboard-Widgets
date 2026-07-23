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
            <div class="fi-fdw-variance">
                @foreach ($items as $item)
                    @php
                        $itemUrl = $item->getUrl();
                        $itemTag = filled($itemUrl) ? 'a' : 'div';
                        $side = $item->isPositive() ? 'pos' : 'neg';
                        $half = $item->getBarWidth($maxAbsoluteChange) / 2;
                        $percentageChange = $item->getFormattedPercentageChange();
                    @endphp

                    <{{ $itemTag }}
                        @if (filled($itemUrl))
                            href="{{ $itemUrl }}"
                            @if ($item->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
                        @endif
                        style="{{ get_color_css_variables($item->getChangeColor(), [400, 500, 600]) }}"
                        class="fi-fdw-variance__item"
                    >
                        <div class="fi-fdw-variance__head">
                            <span class="fi-fdw-variance__label">{{ $item->getLabel() }}</span>

                            <span class="fi-fdw-variance__values">
                                <span class="fi-fdw-variance__value">{{ $item->getFormattedValue() }}</span>
                                <span class="fi-fdw-variance__delta">
                                    <x-filament::icon :icon="$item->getChangeIcon()" />
                                    {{ $item->getFormattedChange() }}@if ($percentageChange !== null) <span class="fi-fdw-variance__percentage">({{ $percentageChange }})</span>@endif
                                </span>
                            </span>
                        </div>

                        <div class="fi-fdw-variance__track">
                            <span class="fi-fdw-variance__axis"></span>

                            @if ($item->getDirection() !== 'neutral')
                                <span
                                    class="fi-fdw-variance__bar fi-fdw-variance__bar--{{ $side }}"
                                    style="width: {{ $half }}%"
                                ></span>
                            @endif
                        </div>
                    </{{ $itemTag }}>
                @endforeach
            </div>
        @endunless
    </div>
</x-filament-widgets::widget>
