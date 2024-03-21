<?php

namespace Innova\Types\Fields;

use Exception;

class FileField
{
    private mixed $fileField;

    public function __construct(private readonly array $settings) {
        if(!isset($this->settings['name'])) {
            throw new Exception('Field name is not set');
        }
    }

    public function create(): FileField {
        $input = [];
        foreach ($this->settings as $key=>$setting) {

            if($key === 'type') {
                $input[] = "$key='$setting'";
            }
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
        $input[] = "class='form-control field-input-file'";
        $input[] = "id='text-{$this->settings['name']}'";
        $line = implode(' ', $input);

        if(!empty($this->settings['label']['visible']) && $this->settings['label']['visible'] === true) {
            $required = empty($this->settings['required']) ? '' : 'required-field';

            $label = "<label for='text-{$this->settings['name']}' class='field-label $required'>{$this->settings['label']['text']}</label>";
            $this->fileField = "<div class='form-group form-file-{$this->settings['name']}-group'>$label<input $line /></div>";
            return $this;
        }

        $this->fileField = "<input $line />";
        return $this;
    }

    public function __toString(): string {
        return html_entity_decode($this->fileField);
    }

    public static function fileField(array $settings): FileField
    {
        return (new FileField($settings))->create();
    }
}