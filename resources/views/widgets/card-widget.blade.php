@php
    use function Filament\Support\get_color_css_variables;

    $variant = $card->getVariant();
    $color = $card->getColor() ?? 'primary';
    $url = $card->getUrl();
    $isLinked = filled($url) && $variant !== 'cta';
    $tag = $isLinked ? 'a' : 'div';
    $showArrow = $isLinked && in_array($variant, ['icon', 'content'], true);
@endphp

<x-filament-widgets::widget>
    <{{ $tag }}
        @if ($isLinked)
            href="{{ $url }}"
            @if ($card->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
        @endif
        @if (filled($pollingInterval)) wire:poll.{{ $pollingInterval }} @endif
        style="{{ get_color_css_variables($color, [100, 400, 500, 600]) }}"
        @class([
            'fi-fdw-card',
            'fi-fdw-cardw',
            'fi-fdw-cardw--'.$variant,
            'fi-fdw-card--interactive' => $isLinked,
        ])
    >
        @switch($variant)
            @case('stat')
                <div class="fi-fdw-cardw__stat-head">
                    <span class="fi-fdw-label">{{ $card->getHeading() }}</span>

                    @if ($card->hasIcon())
                        <span class="fi-fdw-cardw__stat-icon" aria-hidden="true">
                            <x-filament::icon :icon="$card->getIcon()" />
                        </span>
                    @endif
                </div>

                @if ($card->hasValue())
                    <div class="fi-fdw-cardw__value-row">
                        <span class="fi-fdw-cardw__value">{{ $card->getFormattedValue() }}</span>

                        @if ($card->hasBadge())
                            <x-filament::badge :color="$card->getBadgeColor() ?? $color">
                                {{ $card->getBadge() }}
                            </x-filament::badge>
                        @endif
                    </div>
                @endif

                @if ($card->hasDescription())
                    <p class="fi-fdw-description">{{ $card->getDescription() }}</p>
                @endif

                @break

            @case('icon')
                <div class="fi-fdw-cardw__media">
                    <span class="fi-fdw-cardw__tile" aria-hidden="true">
                        @if ($card->hasIcon())
                            <x-filament::icon :icon="$card->getIcon()" />
                        @endif
                    </span>

                    <div class="fi-fdw-cardw__body">
                        <div class="fi-fdw-cardw__title-row">
                            <h3 class="fi-fdw-cardw__title">{{ $card->getHeading() }}</h3>

                            @if ($card->hasBadge())
                                <x-filament::badge :color="$card->getBadgeColor() ?? 'gray'">
                                    {{ $card->getBadge() }}
                                </x-filament::badge>
                            @endif
                        </div>

                        @if ($card->hasDescription())
                            <p class="fi-fdw-description">{{ $card->getDescription() }}</p>
                        @endif
                    </div>

                    @if ($showArrow)
                        <span class="fi-fdw-cardw__arrow" aria-hidden="true">
                            <x-filament::icon icon="heroicon-m-arrow-right" />
                        </span>
                    @endif
                </div>

                @break

            @case('cta')
                @if ($card->hasIcon())
                    <span class="fi-fdw-cardw__tile" aria-hidden="true">
                        <x-filament::icon :icon="$card->getIcon()" />
                    </span>
                @endif

                <h3 class="fi-fdw-cardw__title">{{ $card->getHeading() }}</h3>

                @if ($card->hasDescription())
                    <p class="fi-fdw-description">{{ $card->getDescription() }}</p>
                @endif

                @if ($card->hasActions())
                    <div class="fi-fdw-cardw__actions">
                        @foreach ($card->getActions() as $index => $action)
                            <x-filament::button
                                tag="a"
                                :href="$action->getUrl()"
                                :target="$action->shouldOpenUrlInNewTab() ? '_blank' : null"
                                :color="$action->getColor() ?? ($index === 0 ? $color : 'gray')"
                                :outlined="$index !== 0"
                                :icon="$action->getIcon()"
                            >
                                {{ $action->getLabel() }}
                            </x-filament::button>
                        @endforeach
                    </div>
                @endif

                @break

            @default
                <div class="fi-fdw-cardw__title-row">
                    <h3 class="fi-fdw-cardw__title">
                        @if ($card->hasIcon())
                            <span class="fi-fdw-cardw__title-icon" aria-hidden="true">
                                <x-filament::icon :icon="$card->getIcon()" />
                            </span>
                        @endif

                        <span>{{ $card->getHeading() }}</span>
                    </h3>

                    @if ($card->hasBadge())
                        <x-filament::badge :color="$card->getBadgeColor() ?? 'gray'">
                            {{ $card->getBadge() }}
                        </x-filament::badge>
                    @endif
                </div>

                @if ($card->hasDescription())
                    <p class="fi-fdw-cardw__text">{{ $card->getDescription() }}</p>
                @endif

                @if ($card->hasActions())
                    <div class="fi-fdw-footer">
                        @foreach ($card->getActions() as $action)
                            <x-filament::link
                                :href="$action->getUrl()"
                                :target="$action->shouldOpenUrlInNewTab() ? '_blank' : null"
                                :icon="$action->getIcon()"
                                icon-position="after"
                            >
                                {{ $action->getLabel() }}
                            </x-filament::link>
                        @endforeach
                    </div>
                @endif
        @endswitch

        @if ($showArrow && $variant === 'content')
            <span class="fi-fdw-cardw__arrow fi-fdw-cardw__arrow--corner" aria-hidden="true">
                <x-filament::icon icon="heroicon-m-arrow-right" />
            </span>
        @endif
    </{{ $tag }}>
</x-filament-widgets::widget>
