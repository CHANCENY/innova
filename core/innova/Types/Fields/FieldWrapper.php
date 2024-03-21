<?php

namespace Innova\Types\Fields;

class FieldWrapper
{
    public static function field(array $field): mixed{
        if($field['type'] === 'select') {
           return SelectField::selectField($field)->__toString();
        }

        if($field['type'] === 'email') {
            return EmailField::emailField($field)->__toString();
        }

        if($field['type'] === 'number') {
            return NumberField::numberField($field)->__toString();
        }

        if($field['type'] === 'file') {
            return FileField::fileField($field)->__toString();
        }
        else {
            return TextField::textField($field)->__toString();
        }

    }
}