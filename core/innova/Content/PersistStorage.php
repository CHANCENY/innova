<?php

namespace Innova\Content;

use Innova\Configs\ConfigHandler;
use Innova\Exceptions\MissingFieldException;
use Innova\modules\StorageDefinition;

class PersistStorage {
  private ContentType $contentType;

  public function __construct(private readonly string $contentTypeName) {
    $this->contentType = (new ContentType())->contentType($this->contentTypeName);
  }

  /**
   * Create storage of a content type.
   * @return bool
   * @throws MissingFieldException
   */
  public function persistContent(): bool
  {
    $table = $this->contentType->machineName;
    $fields = $this->contentType->getContentDefinitions()['fields'] ?? [];
    $data = [];
    $old = new StorageDefinition();
    $oldTables = $old->storages()->getStorages();
    if(in_array($table, $oldTables)) {
      return true;
    }
    foreach ($fields as $field)
    {
      if(!empty($field['name']) && !empty($field['storage_definition']))
      {
        $newColumn[$field['name']] = $field['storage_definition'];
        $data['columns'] = $newColumn;
      }
    }

    if(!empty($data))
    {
      $data['columns'][$table."__id"] = [
        "number",
        "primary key",
        "auto_increment",
      ];
      $data['install'] = true;
    }
    if(!empty($data['columns'][$table."__id"]))
    {
      $data['indexes'] = [$table."__id" => $table."__id". "_indexes"];
    }

    $data["table"] = $table;
    $add = new StorageDefinition();
    if($add->addDefinition(array_reverse($data))) {
      return true;
    }
    return false;
  }
}