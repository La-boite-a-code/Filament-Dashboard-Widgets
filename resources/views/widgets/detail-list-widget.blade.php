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
            <dl class="fi-fdw-details">
                @foreach ($details as $detail)
                    @php
                        $detailColor = $detail->getColor() ?? 'gray';
                        $detailUrl = $detail->getUrl();
                    @endphp

                    <div class="fi-fdw-details__row">
                        <dt class="fi-fdw-details__label">
                            @if ($detail->hasIcon())
                                <span
                                    class="fi-fdw-details__icon"
                                    style="{{ get_color_css_variables($detailColor, [400, 500, 600]) }}"
                                    aria-hidden="true"
                                >
                                    <x-filament::icon :icon="$detail->getIcon()" />
                                </span>
                            @endif

                            <span>{{ $detail->getLabel() }}</span>
                        </dt>

                        <dd class="fi-fdw-details__value">
                            @if ($detail->hasValue() || ! $detail->hasBadge())
                                @if (filled($detailUrl))
                                    <x-filament::link
                                        :href="$detailUrl"
                                        :target="$detail->shouldOpenUrlInNewTab() ? '_blank' : null"
                                    >
                                        {{ $detail->getFormattedValue() }}
                                    </x-filament::link>
                                @else
                                    <span>{{ $detail->getFormattedValue() }}</span>
                                @endif
                            @endif

                            @if ($detail->hasBadge())
                                <x-filament::badge :color="$detail->getBadgeColor() ?? 'gray'">
                                    {{ $detail->getBadge() }}
                                </x-filament::badge>
                            @endif
                        </dd>
                    </div>
                @endforeach
            </dl>
        @endunless
    </div>
</x-filament-widgets::widget>
