<?php

namespace Innova\Middlewares;

use Innova\Configs\ConfigHandler;
use Innova\interfaces\AuthenticationMethodInterface;

abstract class Authentications implements AuthenticationMethodInterface{
  private mixed $authenticationMethods;
  private mixed $authenticationMethod;

  public function setIsDefault(mixed $isDefault): void {
    $this->isDefault = $isDefault;
  }

  public function setTransmission(mixed $transmission): void {
    $this->transmission = $transmission;
  }
  private mixed $authenticationView;
  /**
   * @var mixed|true
   */
  private mixed $isDefault;
  /**
   * @var mixed|string
   */
  private mixed $transmission;

  public function getTransmission(): mixed {
    return $this->transmission;
  }

  public function getIsDefault(): mixed {
    return $this->isDefault;
  }

  public function __construct() {
    $this->isDefault = true;
    $this->authenticationView = "user_login/form.php";
    $this->authenticationMethod = "normal";

    $this->authenticationMethods = ConfigHandler::config("authentications");
    if(!empty($this->authenticationMethods)) {
      foreach ($this->authenticationMethods as $key => $authenticationMethod) {
        if($authenticationMethod['active'] === true) {
          $this->authenticationMethod = $key;
          $this->authenticationView = $authenticationMethod['view'];
          $this->isDefault = $authenticationMethod['isDefault'] ?? true;
          $this->transmission = $authenticationMethod['transmission'] ?? "email";
          break;
        }
      }
    }
  }

  public function setAuthenticationView(mixed $authenticationView): void {
    $this->authenticationView = $authenticationView;
  }

  public function setAuthenticationMethods(mixed $authenticationMethods): void {
    $this->authenticationMethods = $authenticationMethods;
  }

  public function setAuthenticationMethod(string $method = "normal"): void {
    $this->authenticationMethod = $this->authenticationMethod === $method ? $this->authenticationMethod : $method;
  }

  public function getAuthenticationMethod(): string {
    return $this->authenticationMethod;
  }

  public function getViewTemplate(): string {
    return $this->authenticationView;
  }

}