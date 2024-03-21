<?php

namespace Innova\Types\Fields;

class ActionButtons
{
    private string|null $actions;
    public function __construct(private array $options)
    {
        $this->actions = null;
    }

    public function render():ActionButtons
    {
        foreach ($this->options as $option) {
            $css = $option['class'] ?? null;
            $this->actions .= "<button type='{$option['type']}' name='{$option['name']}' id='{$option['id']}' class='btn action-form-btn {$css}'>{$option['text']}</button>";

        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->actions;
    }

    public static function actions(array $options): ActionButtons
    {
        return (new ActionButtons($options))->render();
    }
}