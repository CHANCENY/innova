<?php

namespace Innova\MailManger;

class Attachments {
  private bool $attachment;

  public function isAttachment(): bool {
    return $this->attachment;
  }

  public function getAttachments(): array {
    return $this->attachments;
  }

  public function __construct(private readonly array $attachments) {
    $this->attachment = false;
    if(!empty($this->attachments))
    {
      $this->attachment = true;
    }
  }
}