<?php

namespace Livewire\Features\SupportAttributes;

use Livewire\Component;

abstract class Attribute
{
    protected Component $component;

    protected AttributeLevel $level;

    protected $levelName;

    function __boot($component, AttributeLevel $level, $name = null)
    {
        $this->component = $component;
        $this->level = $level;
        $this->levelName = $name;
    }

    function getComponent()
    {
        return $this->component;
    }

    function getLevel()
    {
        return $this->level;
    }

    function getName()
    {
        return $this->levelName;
    }

    function getValue()
    {
        if ($this->level !== AttributeLevel::PROPERTY) {
            throw new \Exception('Can\'t set the value of a non-property attribute.');
        }

        return data_get($this->component->all(), $this->levelName);
    }

    function setValue($value)
    {
        if ($this->level !== AttributeLevel::PROPERTY) {
            throw new \Exception('Can\'t set the value of a non-property attribute.');
        }

        data_set($this->component, $this->levelName, $value);
    }
}
