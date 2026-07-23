<?php

declare(strict_types=1);

namespace LaBoiteACode\FilamentDashboardWidgets\Concerns;

use Closure;

trait EvaluatesClosures
{
    /**
     * Resolve a value that may be a closure. Any extra arguments are forwarded
     * to the closure, allowing setters to accept both static values and
     * callbacks evaluated lazily at render time.
     */
    protected function evaluate(mixed $value, mixed ...$arguments): mixed
    {
        if ($value instanceof Closure) {
            return $value(...$arguments);
        }

        return $value;
    }
}
