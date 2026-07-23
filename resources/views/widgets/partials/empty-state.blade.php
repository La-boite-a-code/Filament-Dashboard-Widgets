<div class="fi-fdw-empty">
    @if (filled($icon))
        <span class="fi-fdw-empty__icon" aria-hidden="true">
            @svg($icon)
        </span>
    @endif

    <p class="fi-fdw-empty__heading">{{ $heading }}</p>

    @if (filled($description))
        <p class="fi-fdw-empty__description">{{ $description }}</p>
    @endif
</div>
