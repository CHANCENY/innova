<?php

namespace Innova\interfaces;

interface AuthAuthenticationInterface {
  public function settingWarning(): void;

  public function enablingUser(int $uid): bool;

  public function disablingUser(int $uid): bool;

  public function verificationUserCode(string $code, int $uid): bool;
}