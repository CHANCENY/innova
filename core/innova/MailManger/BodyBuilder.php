<?php

namespace Innova\MailManger;

class BodyBuilder {
  private mixed $message;

  public function getMessage(): mixed {
    return $this->message;
  }

  public function getSubject(): mixed {
    return $this->subject;
  }

  public function getParams(): array {
    return $this->params;
  }
  private mixed $subject;

  public function __construct(private readonly array $params) {
    if(!isset($this->params['message']) || !isset($this->params['subject']))
    {
      throw new ParamMissingException("params need to be subject and message");
    }

    $this->message = $this->params['message'];
    $this->subject = $this->params['subject'];
  }
}