<?php

namespace Innova\Types\Fields;

use Exception;

class EmailField
{
    private mixed $emailField;

    public function __construct(private readonly array $settings) {
        if(!isset($this->settings['name'])) {
            throw new Exception('Field name is not set');
        }
    }

    public function create(): EmailField {

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
        $input[] = "class='form-control field-input-email'";
        $input[] = "id='text-{$this->settings['name']}'";
        $line = implode(' ', $input);

        if(!empty($this->settings['label']['visible']) && $this->settings['label']['visible'] === true) {
            $required = empty($this->settings['required']) ? '' : 'required-field';

            $label = "<label for='text-{$this->settings['name']}' class='field-label $required'>{$this->settings['label']['text']}</label>";
            $this->emailField = "<div class='form-group form-email-{$this->settings['name']}-group'>$label<input $line /></div>";
            return $this;
        }

        $this->emailField = "<input $line />";
        return $this;
    }

    public function __toString(): string {
        return html_entity_decode($this->emailField);
    }

    public static function emailField(array $settings): EmailField {
        return (new EmailField($settings))->create();
    }
}