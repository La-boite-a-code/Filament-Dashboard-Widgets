@php
    use function Filament\Support\get_color_css_variables;

    $viewAllAction = $this->getAction('viewAll');
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
            <div class="fi-fdw-recent__list">
                @foreach ($items as $item)
                    @php
                        $itemUrl = $item->getUrl();
                        $itemTag = filled($itemUrl) ? 'a' : 'div';
                        $itemColor = $item->getColor() ?? 'gray';
                    @endphp

                    <{{ $itemTag }}
                        @if (filled($itemUrl))
                            href="{{ $itemUrl }}"
                            @if ($item->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
                        @endif
                        style="{{ get_color_css_variables($itemColor, [400, 500, 600]) }}"
                        class="fi-fdw-recent__item"
                    >
                        @if ($item->hasAvatar() || $item->hasIcon())
                            <span class="fi-fdw-recent__visual" aria-hidden="true">
                                @if ($item->hasAvatar())
                                    <img src="{{ $item->getAvatar() }}" alt="">
                                @else
                                    <x-filament::icon :icon="$item->getIcon()" />
                                @endif
                            </span>
                        @endif

                        <div class="fi-fdw-recent__body">
                            <span class="fi-fdw-recent__title">{{ $item->getTitle() }}</span>

                            @if (filled($item->getDescription()))
                                <span class="fi-fdw-recent__description">{{ $item->getDescription() }}</span>
                            @endif
                        </div>

                        <div class="fi-fdw-recent__aside">
                            @if ($item->hasBadge())
                                <x-filament::badge :color="$item->getBadgeColor() ?? 'gray'">
                                    {{ $item->getBadge() }}
                                </x-filament::badge>
                            @endif

                            @if ($item->hasMeta())
                                <span class="fi-fdw-recent__meta">{{ $item->getMeta() }}</span>
                            @endif
                        </div>
                    </{{ $itemTag }}>
                @endforeach
            </div>

            @if ($viewAllAction?->isVisible())
                <div class="fi-fdw-footer">
                    {{ $viewAllAction }}
                </div>
            @endif
        @endunless
    </div>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
