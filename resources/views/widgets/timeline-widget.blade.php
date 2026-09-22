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
            <div class="fi-fdw-timeline">
                @foreach ($groups as $group)
                    @if (filled($group['label']))
                        <p class="fi-fdw-timeline__group-label">{{ $group['label'] }}</p>
                    @endif

                    <div class="fi-fdw-timeline__list">
                        @foreach ($group['events'] as $event)
                            @php
                                $eventColor = $event->getColor() ?? 'primary';
                                $eventUrl = $event->getUrl();
                                $eventTag = filled($eventUrl) ? 'a' : 'div';
                            @endphp

                            <{{ $eventTag }}
                                @if (filled($eventUrl))
                                    href="{{ $eventUrl }}"
                                    @if ($event->shouldOpenUrlInNewTab()) target="_blank" rel="noopener noreferrer" @endif
                                @endif
                                style="{{ get_color_css_variables($eventColor, [100, 400, 500, 600]) }}"
                                class="fi-fdw-timeline__event"
                            >
                                <span class="fi-fdw-timeline__marker" aria-hidden="true">
                                    @if ($event->hasAvatar())
                                        <img src="{{ $event->getAvatar() }}" alt="">
                                    @elseif ($event->hasIcon())
                                        <x-filament::icon :icon="$event->getIcon()" />
                                    @endif
                                </span>

                                <div class="fi-fdw-timeline__body">
                                    <span class="fi-fdw-timeline__title">{{ $event->getTitle() }}</span>

                                    @if (filled($event->getDescription()))
                                        <span class="fi-fdw-timeline__description">{{ $event->getDescription() }}</span>
                                    @endif

                                    <div class="fi-fdw-timeline__meta">
                                        @if ($event->hasTimestamp())
                                            <time class="fi-fdw-timeline__time" datetime="{{ $event->getDateTimeString() }}">
                                                {{ $event->getRelativeTime() }}
                                            </time>
                                        @endif

                                        @if ($event->hasActor())
                                            <span class="fi-fdw-timeline__time">{{ $event->getActor() }}</span>
                                        @endif

                                        @if ($event->hasBadge())
                                            <x-filament::badge :color="$event->getBadgeColor() ?? 'gray'">
                                                {{ $event->getBadge() }}
                                            </x-filament::badge>
                                        @endif
                                    </div>
                                </div>
                            </{{ $eventTag }}>
                        @endforeach
                    </div>
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
