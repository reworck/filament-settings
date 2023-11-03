<?php

namespace Livewire\Features\SupportLockedProperties;

use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

#[\Attribute]
class BaseLocked extends LivewireAttribute
{
    public function update()
    {
        throw new \Exception('Cannot update locked property: ['.$this->getName().']');
    }
}
