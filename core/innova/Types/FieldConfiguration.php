<?php

namespace Innova\Types;
class FieldConfiguration
{

    private string $fieldName;

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function setFieldName(string $fieldName): void
    {
        $this->fieldName = $fieldName;
    }

    public function getFieldLabel(): string
    {
        return $this->fieldLabel;
    }

    public function setFieldLabel(string $fieldLabel): void
    {
        $this->fieldLabel = $fieldLabel;
    }

    private string $fieldLabel;

    /**
     * @param string $field_name
     */
    public function __construct(string $field_name)
    {
        $this->fieldLabel = $field_name;
        $this->fieldName = strtolower(str_replace(' ','_', $field_name));
    }
}