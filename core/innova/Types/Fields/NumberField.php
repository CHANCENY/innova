<?php

namespace Innova\Types\Fields;

use Exception;

class NumberField
{
    private mixed $numberField;

    public function __construct(private readonly array $settings) {
        if(!isset($this->settings['name'])) {
            throw new Exception('Field name is not set');
        }
    }

    public function create(): NumberField {

        $input = [];
        foreach ($this->settings as $key=>$setting) {

            if($key === 'type') {
                $input[] = "$key='$setting'";
            }
            if($key === 'required' && $setting === true) {
                $input[] = "required";
            }

            if($key === 'name') {
                $input[] = "name='$setting'";
            }

            if($key === 'readonly' && $setting === true) {
                $input[] = "readonly";
            }

            if($key === 'min') {
                $input[] = "min='$setting'";
            }

            if($key === 'max') {
                $input[] = "max='$setting'";
            }

            if($key === 'placeholder') {
                $input[] = "placeholder='$setting'";
            }
        }
        $input[] = "class='form-control field-input-number'";
        $input[] = "id='text-{$this->settings['name']}'";
        $line = implode(' ', $input);

        if(!empty($this->settings['label']['visible']) && $this->settings['label']['visible'] === true) {
            $required = empty($this->settings['required']) ? '' : 'required-field';

            $label = "<label for='text-{$this->settings['name']}' class='field-label $required'>{$this->settings['label']['text']}</label>";
            $this->numberField = "<div class='form-group form-number-{$this->settings['name']}-group'>$label<input $line /></div>";
            return $this;
        }

        $this->numberField = "<input $line />";
        return $this;
    }

    public function __toString(): string {
        return html_entity_decode($this->numberField);
    }

    public static function numberField(array $settings): NumberField {
        return (new NumberField($settings))->create();
    }
}