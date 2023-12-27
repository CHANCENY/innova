<?php
namespace Innova\MailManger;

class Reciever {
  public function __construct(
    private readonly array $recieverAddress,
    private readonly array $recieverNames
  ){}

  public function getRecieverAddress(): array {
    return $this->recieverAddress;
  }

  public function getRecieverNames(): array {
    return $this->recieverNames;
  }
}