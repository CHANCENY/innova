<?php

namespace Innova\Types\Fields;
use Exception;

class TextField
{
    private mixed $textField;

    public function __construct(private readonly array $settings) {
        if(!isset($this->settings['name'])) {
            throw new Exception('Field name is not set');
        }
    }

    public function create(): TextField {
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

            if($key === 'placeholder') {
                $input[] = "placeholder='$setting'";
            }
        }
        $input[] = "class='form-control field-input-text'";
        $input[] = "id='text-{$this->settings['name']}'";
        $line = implode(' ', $input);

        if(!empty($this->settings['label']['visible']) && $this->settings['label']['visible'] === true) {
            $required = empty($this->settings['required']) ? '' : 'required-field';

            $label = "<label for='text-{$this->settings['name']}' class='field-label $required'>{$this->settings['label']['text']}</label>";
            $this->textField = "<div class='form-group form-text-{$this->settings['name']}-group'>$label<input $line /></div>";
            return $this;
        }

        $this->textField = "<input $line />";
        return $this;
    }

    public function __toString(): string {
        return html_entity_decode($this->textField);
    }

    public static function textField(array $settings): TextField {
        return (new TextField($settings))->create();
    }
}