<?php

namespace Innova\MailManger;

class BCC {
  public bool $bcc;

  public function isBcc(): bool {
    return $this->bcc;
  }

  public function getBccAddresses(): array {
    return $this->bccAddresses;
  }

  public function __construct(
  private readonly array $bccAddresses
) {
    $this->bcc = false;
  if(!empty($this->bccAddresses))
  {
    $this->bcc = true;
  }
}
}