<?php

namespace Innova\Content;

use Innova\Configs\ConfigHandler;
use Innova\Databases\Tables;
use Innova\Exceptions\ExceptionDatabaseConnection;

class ContentType extends ContentStates {
  private array $contentDefinitions;
  public string $machineName;

  public function getContentDefinitions(): array {
    return $this->contentDefinitions;
  }

  /**
   * Initialization
   */
  public function __construct() {
    $this->error = false;
    $this->message = "";
    $this->contentDefinitions = [];
  }

  /**
   * Content Type creation
   * @param string $contentName Name of Content to Create
   *
   * @return $this
   * This Object
   */
  public function create(string $contentName, string $active = "on", string $description = "", array $permissions = []): ContentType
  {
    if(!is_dir($this->storage))
    {
      if(!mkdir($this->storage, 7777, true)){
        $this->error = true;
        $this->message = "Permissions or location $this->storage not found";
        return $this;
      }
    }
    $this->machineName = "content_".str_replace(
        [' ', '/', '-', '@', '#'],
        ['_', '_', '_', '_', '_'],
        $contentName
      );
    $completeName = $this->storage . "/". $this->machineName. ".json";
    if(file_exists($completeName)){
      $this->error = true;
      $this->message = "Content type with machine name '$this->machineName' and name '$contentName' already exist";
      return $this;
    }
    $this->contentDefinitions = [
      "label" => $contentName,
      "name" => $this->machineName,
      "description" => $description,
      "permissions" => $permissions,
      "fields" => [
        [
          "label" => "Title",
          "name" => "title",
          "type" => "text_field",
          "form_display" => true,
          "page_display" => true,
          "description" => "",
          "storage_definition" => [
            "short text",
            "not empty"
          ]
        ]
      ],
      "active" => !empty($active)
    ];

    if(file_put_contents($completeName,
      (new ConfigHandler())->formatter(json_encode($this->contentDefinitions,JSON_PRETTY_PRINT))))
    {
      $this->error = false;
      $this->message = "Content Type Created successfully";
      return $this;
    }
    $this->error = true;
    $this->message = "Content Type Creation Failed due to permission @$completeName";
    return $this;
  }

  /**
   * @param string $contentName Name of content type content:sample
   *
   * @return $this
   * @throws ExceptionDatabaseConnection
   */
  public function remove(string $contentName): ContentType
  {
    $fullname = trim($this->storage,'/') . "/$contentName.json";
    if(file_exists($fullname))
    {
      if(Tables::removeTable($contentName)){
        unlink($fullname);
        $this->error = false;
        $this->message = "Successfully deleted content type";
        return $this;
      }
    }
    $this->error = true;
    $this->message = "Failed to delete content type";
    return $this;
  }

  /**
   * Load Content type definitions.
   * @param string $contentName Name of a content type.
   *
   * @return $this
   */
  public function contentType(string $contentName): ContentType
  {
    $completeName = $this->storage . "/". $contentName. ".json";
    if(file_exists($completeName)) {
      $this->contentDefinitions = json_decode(file_get_contents($completeName), true);
      if(!empty($this->contentDefinitions)) {
        $this->machineName = $this->contentDefinitions['name'];
      }
    }
    return $this;
  }

  public function contentListing() : array
  {
    $list = [];
    if(is_dir($this->storage))
    {
      $list = array_diff(scandir($this->storage), ['.', '..', '...']);
    }

    $contents = [];
    foreach ($list as $value)
    {
      $filename = $this->storage . "/$value";
      $content = json_decode(file_get_contents($filename), TRUE);
      if(!empty($content['name']) && $content['label'])
      {
        $contents[] = [
          "name" => $content['name'],
          "label" => $content['label']
        ];
      }
    }
    return $contents;
  }
}