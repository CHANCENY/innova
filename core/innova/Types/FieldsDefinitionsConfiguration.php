<?php

namespace Innova\Types;
class FieldsDefinitionsConfiguration
{

    public bool $error;

    public string|null $message;
    private array $field;

    public function isError(): bool
    {
        return $this->error;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function getField(): array
    {
        return $this->field;
    }

    public function __construct(private ContentType $contentType)
    {
        $this->error = false;
        $this->message = null;
        $this->field = [];
    }

    public function newField(string $field_name, array $settings): FieldsDefinitionsConfiguration
    {
        if(!empty($this->contentType->contentType['fields'])) {
            // Let's look for field name if exist
            foreach ($this->contentType->contentType['fields'] as $ky=>$config) {
                if($config instanceof FieldConfiguration) {
                    if($config->getFieldName() === strtolower(str_replace(' ','_', $field_name))) {
                       $this->error = true;
                       $this->message = 'Field with machine name '. $config->getFieldName() . ' already exist';
                    }
                }
            }
            if(!$this->error) {
                $this->field = [
                    'config' => new FieldConfiguration($field_name),
                    'settings' => new FieldStorageSettings($settings),
                ];
            }
        }
        return $this;
    }
}