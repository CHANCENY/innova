<?php

namespace Innova\Types;

use Innova\Configs\ConfigHandler;

class ContentTypeStorage
{
    private ContentType|null $definitions;

    public function __construct(private string $content_type)
    {
        $this->definitions = null;
        $config = ConfigHandler::config('content_configuration');
        if(!empty($config)) {
            foreach ($config as $item) {
                if(!empty($item['key']) && $item['key'] === $this->content_type) {
                    $base64 = $item['settings'];
                    $serialized = unserialize(base64_decode($base64));
                    if($serialized) {
                        $this->definitions = $serialized;
                    }

                }
            }
        }
    }

    public function query(): void
    {
      $fields = $this->definitions->contentType['fields'] ?? null;
      $bundle = $this->definitions->contentType['bundle'] ?? null;
      if($fields && $bundle instanceof ContentTypeSettings) {
          $name = $bundle->getContentTypeName();
          foreach ($fields as $field) {
              if($field instanceof FieldsDefinitionsConfiguration) {
                  $fieldConfig = $field->getField();
                  $config = $fieldConfig['config'] ?? null;
                  $settings = $fieldConfig['settings'] ?? null;
                  $columns = [];

                  if($config instanceof FieldConfiguration && $settings instanceof FieldStorageSettings) {
                      $storage = $settings->getSettings();
                      if(isset($storage['is_table']) && $storage['is_table'] === true) {
                          $table = $name . '_' . $config->getFieldName();
                          $columns[] = $storage['type'] ?? 'varchar(255)';
                          $columns[] = $storage['is_null'] === false ? 'NOT NULL' : 'NULL';

                          if($storage['is_null'] === false) {
                              $default = $storage['default'] ?? null;
                              if($default) {
                                  $columns[] = $default();
                              }
                          }

                      }
                  }
              }
          }
      }
    }
}