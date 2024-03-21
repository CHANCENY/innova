<?php

namespace Innova\Types\Fields;

use Exception;

class SelectField
{
    private mixed $selectField;

    public function __construct(private readonly array $settings) {
        if(!isset($this->settings['name'])) {
            throw new Exception('Field name is not set');
        }
    }

    public function create(): SelectField {
        $input = [];
        foreach ($this->settings as $key=>$setting) {

            if($key === 'required' && $setting === true) {
                $input[] = "required";
            }

            if($key === 'name') {

                if(!empty($this->settings['multiple'])) {
                    $input[] = "name='{$setting}[]'";
                }else {
                    $input[] = "name='$setting'";
                }
            }

            if($key === 'readonly' && $setting === true) {
                $input[] = "readonly";
            }

            if($key === 'multiple' && $setting === true) {
                $input[] = "multiple";
            }
        }

        $options = "<option>Select</option>";
        foreach ($this->settings['options'] as $key=>$value) {

            if($this->settings['default_value'] === $key) {
                $options .= "<option value='$key' selected>$value</option>";
            }else {
                $options .= "<option value='$key'>$value</option>";
            }

        }

        $input[] = "class='form-control field-select-file'";
        $input[] = "id='select-{$this->settings['name']}'";
        $line = "<select " .implode(' ', $input) .">". $options ."</select>";

        if(!empty($this->settings['label']['visible']) && $this->settings['label']['visible'] === true) {
            $required = empty($this->settings['required']) ? '' : 'required-field';

            $label = "<label for='text-{$this->settings['name']}' class='field-label $required'>{$this->settings['label']['text']}</label>";
            $this->selectField = "<div class='form-group form-select-{$this->settings['name']}-group'>$label $line</div>";
            return $this;
        }

        $this->selectField = "<input $line />";
        return $this;
    }

    public function __toString(): string {
        return html_entity_decode($this->selectField);
    }

    public static function selectField(array $settings): SelectField
    {
        return (new SelectField($settings))->create();
    }
}