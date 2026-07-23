@php
    $headingText = $heading ?? null;
    $headingDescriptionText = $description ?? null;
@endphp

@if (filled($headingText) || filled($headingDescriptionText))
    <div class="fi-fdw-heading">
        @if (filled($headingText))
            <h3 class="fi-fdw-heading__title">{{ $headingText }}</h3>
        @endif

        @if (filled($headingDescriptionText))
            <p class="fi-fdw-heading__description">{{ $headingDescriptionText }}</p>
        @endif
    </div>
@endif
