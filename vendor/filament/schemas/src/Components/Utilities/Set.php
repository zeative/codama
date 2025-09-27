<?php

namespace Filament\Schemas\Components\Utilities;

use Filament\Schemas\Components\Component;

class Set
{
    public function __construct(
        protected Component $component,
    ) {}

    public function __invoke(string | Component $path, mixed $state, bool $isAbsolute = false, bool $shouldCallUpdatedHooks = false): mixed
    {
        $livewire = $this->component->getLivewire();

        $path = $this->component->resolveRelativeStatePath($path, $isAbsolute);

        $component = $this->component->getRootContainer()->getComponentByStatePath(
            $path,
            withHidden: true,
            withAbsoluteStatePath: true,
            skipComponentChildContainersWhileSearching: $this->component,
        );

        $state = $this->component->evaluate($state);

        if ($component) {
            $component->state($state);
            $shouldCallUpdatedHooks && $component->callAfterStateUpdated();
        } else {
            data_set($livewire, $path, $state);
        }

        return $state;
    }
}
