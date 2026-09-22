<?php

declare(strict_types=1);

use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\ContentCardWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\CtaCardWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\IconCardWidget;
use LaBoiteACode\FilamentDashboardWidgets\Tests\Fixtures\Widgets\StatCardWidget;
use Livewire\Livewire;

it('renders the stat variant with a formatted value and a badge', function (): void {
    Livewire::test(StatCardWidget::class)
        ->assertSee('Monthly revenue')
        ->assertSee('$24,850')
        ->assertSee('+12.4%')
        ->assertSee('Compared to last month')
        ->assertSeeHtml('fi-fdw-cardw--stat');
});

it('renders the icon variant as an interactive link', function (): void {
    Livewire::test(IconCardWidget::class)
        ->assertSee('API documentation')
        ->assertSee('Explore the reference and guides')
        ->assertSeeHtml('fi-fdw-cardw--icon')
        ->assertSeeHtml('fi-fdw-card--interactive')
        ->assertSeeHtml('href="https://example.test/docs"');
});

it('renders the content variant with a badge and a footer link', function (): void {
    Livewire::test(ContentCardWidget::class)
        ->assertSee('Release notes')
        ->assertSee('New')
        ->assertSee('Version 2.0 ships multi series charts, usage meters and cards.')
        ->assertSee('Read more')
        ->assertSeeHtml('href="https://example.test/changelog"');
});

it('renders the cta variant with several action buttons', function (): void {
    Livewire::test(CtaCardWidget::class)
        ->assertSee('Invite your team')
        ->assertSee('Collaborate by adding your colleagues to the workspace.')
        ->assertSee('Send invites')
        ->assertSee('Learn more')
        ->assertSeeHtml('fi-fdw-cardw--cta')
        ->assertSeeHtml('href="/team/invite"')
        ->assertSeeHtml('href="/docs/team"');
});

it('renders card descriptions with the native description scale', function (): void {
    Livewire::test(IconCardWidget::class)
        ->assertSeeHtml('<p class="fi-fdw-description">Explore the reference and guides</p>');
});
