<?php

namespace Innova\interfaces;

interface AuthenticationMethodInterface {
  public function setAuthenticationMethod(string $method = "normal"): void;

  public function getAuthenticationMethod(): string;

  public function getViewTemplate(): string;
}