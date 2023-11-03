<?php

namespace Livewire\Features\SupportValidation;

use Attribute;
use Livewire\Features\SupportAttributes\Attribute as LivewireAttribute;

use function Livewire\wrap;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_ALL)]
class BaseRule extends LivewireAttribute
{
    function __construct(
        public $rule,
        protected $attribute = null,
        protected $as = null,
        protected $message = null,
        protected $onUpdate = true,
        protected bool $translate = true
    ) {}

    function boot()
    {
        $rules = [];

        // Support setting rules by key-value for this and other properties:
        // For example, #[Rule(['foo' => 'required', 'foo.*' => 'required'])]
        if (is_array($this->rule) && count($this->rule) > 0 && ! is_numeric(array_keys($this->rule)[0])) {
            $rules = $this->rule;
        } else {
            $rules[$this->getName()] = $this->rule;
        }

        // @todo: make this more robust (account for FormObjects that
        // aren't named "form")...
        if (str($this->getName())->startsWith('form.')) {
            $name = (string) str($this->getName())->after('form.');

            $this->component->addValidationAttributesFromOutside([$this->getName() => $name]);
        }

        if ($this->attribute) {
            if (is_array($this->attribute)) {
                $this->component->addValidationAttributesFromOutside($this->attribute);
            } else {
                $this->component->addValidationAttributesFromOutside([$this->getName() => $this->attribute]);
            }
        }

        if ($this->as) {
            if (is_array($this->as)) {
                $as = $this->translate
                    ? array_map(fn ($i) => trans($i), $this->as)
                    : $this->as;

                $this->component->addValidationAttributesFromOutside($as);
            } else {
                $this->component->addValidationAttributesFromOutside([$this->getName() => $this->translate ? trans($this->as) : $this->as]);
            }
        }

        if ($this->message) {
            if (is_array($this->message)) {
                $messages = $this->translate
                    ? array_map(fn ($i) => trans($i), $this->message)
                    : $this->message;

                $this->component->addMessagesFromOutside($messages);
            } else {
                // If a single message was provided, apply it to the first given rule.
                // There should have only been one rule provided in this case anyways...
                $rule = head(array_values($rules));

                // In the case of "min:5" or something, we only want "min"...
                $rule = (string) str($rule)->before(':');

                $this->component->addMessagesFromOutside([$this->getName().'.'.$rule => $this->translate ? trans($this->message) : $this->message]);
            }
        }

        $this->component->addRulesFromOutside($rules);
    }

    function update($fullPath, $newValue)
    {
        if ($this->onUpdate === false) return;

        return function () {
            wrap($this->component)->validateOnly($this->getName());
        };
    }
}
