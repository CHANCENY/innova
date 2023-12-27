<?php

namespace Innova\Exceptions;

use Throwable;

class RouteNotFoundException extends \Exception {
  public function __construct(string $message = "", int $code = 1001, ?Throwable $previous = null) {
    parent::__construct($message, $code, $previous);
  }
}