<?php

namespace Innova\Content;

abstract class ContentStates {

  /**
   * @var string Storage of content type definitions.
   */
  protected string $storage = "sites/settings/application/content_types";
  protected bool $error;
  protected string $message;

  public function getMessage(): string {
    return $this->message;
  }

  public function isError(): bool {
    return $this->error;
  }

  /**
   * Overriding default location.
   * @param string $location Writable directory and accessible.
   *
   * @return void
   * Nothing is returned.
   */
  public function overrideStorageLocation(string $location): void
  {
    $list = [];
    if(is_dir($this->storage)) {
      $list = array_diff(scandir($this->storage), ['.', '..', '...']);
    }
    $copied = [];
    if(!empty($list) && is_dir($location)) {
      foreach ($list as $item) {
        $file = $this->storage . "/$item";
        if(file_exists($file)) {
          $copied[] = copy($file, trim($location, "/") . "/$item");
        }
      }
    }
    $results = array_filter($copied);
    if(count($results) === count($list)) {
      $this->storage = $location;
    }
    else
    {
      if(!empty($results)) {
        foreach ($list as $file) {
          $file = trim($location) . "/$file";
          if(file_exists($file)) {
            unlink($file);
          }
        }
      }
      $this->error = true;
      $this->message = "Failed to override default location";
    }
  }

}