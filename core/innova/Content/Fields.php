<?php

namespace Innova\Content;

use Innova\Configs\ConfigHandler;

class Fields extends ContentStates {

  private array $fields;
  private array $fieldContentType;

  public function __construct(readonly protected string $contentType)
  {
    $this->fieldContentType = (new ContentType())->contentType($this->contentType)->getContentDefinitions();
    $this->fields = $this->fieldContentType['fields'] ?? [];
  }

  /**
   * Appending Fields details.
   *
   * @param array $fields
   *
   * @return $this
   * return object.
   */
  public function addField(array $fields): Fields
  {
    foreach ($fields as $key=>$field)
    {
      $fieldSensitized = [];

      if(!empty($field['label']))
      {
        $machine = str_replace([" ", "/", "-", "@", "#"],["_", "_", "_", "_", "_"] ,$field['label']);
        $fieldSensitized['name'] = $machine;
        $fieldSensitized['label'] = $field['label'];
      }

      if(!empty($field['type']))
      {
        $fieldSensitized['type'] = $field['type'];
      }

      if(!empty($field['description']))
      {
        $fieldSensitized['description'] = $field['description'];
      }

      if (!empty($fieldSensitized)) {
        $fieldSensitized['form_display'] = true;
        $fieldSensitized['page_display'] = true;
      }

      if(!empty($field['storage_type']))
      {
        $fieldSensitized['storage_definition'][] =  $field['storage_type'];
      }

      if(!empty($field['storage_mandate']))
      {
        $fieldSensitized['storage_definition'][] = "not empty";
      }else
      {
        $fieldSensitized['storage_definition'][] = "empty";
      }

      $key = array_keys($fieldSensitized);
      $allowed = ['type', 'name', 'label', 'storage_definition'];
      $flag = true;
      foreach ($allowed as $item)
      {
        if(!in_array($item, $key))
        {
          $flag = false;
          break;
        }
      }
      if($flag)
      {
        $exist = false;
        foreach ($this->fields as $field){
          if(!empty($field['name']) && $field['name'] === $fieldSensitized['name'])
          {
            $exist = true;
            break;
          }
        }
        if($exist === false)
        {
          $this->fields[] = $fieldSensitized;
        }
      }
    }
    return $this;
  }

  /**
   * Remove field from fields.
   * @param string $fieldName Name of field.
   *
   * @return $this
   */
  public function removeField(string $fieldName): Fields
  {
    foreach ($this->fields as $key=>$field) {
      if(!empty($field['name']) && $field['name'] === $fieldName)
      {
        unset($this->fields[$key]);
        $this->error = false;
        $this->message = "Field removed";
      }
    }
    return $this;
  }

  /**
   * New update of field.
   * @param string $fieldName Name of field.
   * @param array $newDefinition New updates.
   *
   * @return $this
   */
  public function updateField(string $fieldName, array $newDefinition): Fields
  {
    $found = [];
    $index = 0;
    foreach ($this->fields as $key=>$field)
    {
      if (!empty($field['name']) && $field['name'] === $fieldName)
      {
        $found = $field;
        $index = $key;
        break;
      }
    }

    if(!empty($found))
    {
      foreach ($found as $k=>$item)
      {
        $found[$k] = !empty($newDefinition[$k]) ? $newDefinition[$k] : $item;
      }
    }

    $this->fields[$index] = $found;
    return $this;
  }

  /**
   * Save fields.
   * @return bool
   */
  public function saveFields(): bool
  {
    $content = $this->fieldContentType;
    $content['fields'] = $this->fields;
    $complete = trim($this->storage, "/"). "/" . $this->contentType . ".json";
    if(file_exists($complete)) {
      if(file_put_contents($complete,
        (new ConfigHandler())->formatter(json_encode($content, JSON_PRETTY_PRINT))))
      {
        return true;
      }
    }
    return false;
  }
}