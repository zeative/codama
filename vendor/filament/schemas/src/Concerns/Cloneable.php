<?php

namespace Filament\Schemas\Concerns;

use Closure;

trait Cloneable
{
    /**
     * @var array<Closure>
     */
    protected array $afterCloned = [];

    public function afterCloned(Closure $callback): static
    {
        $this->afterCloned[] = $callback;

        return $this;
    }

    public function getClone(): static
    {
        $clone = clone $this;
        $clone->flushCachedAbsoluteKey();
        $clone->flushCachedAbsoluteStatePath();
        $clone->flushCachedInheritanceKey();
        $clone->cloneComponents();

        foreach ($this->afterCloned as $callback) {
            $clone->evaluate(
                value: $callback->bindTo($clone),
                namedInjections: ['clone' => $clone, 'original' => $this]
            );
        }

        return $clone;
    }
}
