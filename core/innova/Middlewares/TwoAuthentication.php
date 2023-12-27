<?php

namespace Innova\Middlewares;

use Innova\Databases\Query;
use Innova\Databases\Select;
use Innova\Entities\User;
use Innova\interfaces\TwoAuthenticationInterface;
use Innova\MailManger\Mail;
use Innova\MailManger\Reciever;
use Innova\Routes\Routes;

abstract class TwoAuthentication implements TwoAuthenticationInterface {

  private string $transmission;
  private mixed $key;

  public function getKey(): mixed {
    return $this->key;
  }

  public function getTransmission(): string {
    return $this->transmission;
  }

  public function getReceiver(): string {
    return $this->receiver;
  }

  public function getCallBackControllerId(): string {
    return $this->callBackControllerId;
  }

  public function isError(): bool {
    return $this->error;
  }

  public function getUser(): false|array {
    return $this->user;
  }

  public function getCallBackRoute(): mixed {
    return $this->callBackRoute;
  }
  private string $receiver;
  private string $callBackControllerId;
  /**
   * @var true
   */
  private bool $error;
  private array|false $user;
  private mixed $callBackRoute;

  public function __construct() {
    $this->error = false;
    $this->transmission = "";
    $this->receiver = "";
    $this->callBackControllerId = "";
    $this->user = [];
    $this->callBackRoute = [];
  }

  public function setTransmission(string $transmission): void {
    $this->transmission = $transmission;
  }

  public function setOtpReceiver(string $receiver): void {
    $this->receiver = $receiver;
  }

  public function verifyReceiver(): void {
    $user = Query::query(
      "SELECT * FROM users WHERE mail = :mail OR username = :username",
      [
        "mail" => $this->receiver,
        "username" => $this->receiver
      ]
    )->getResult();
    if(empty($user[0]['uid'])){
      $this->error = true;
    }
    else
    {
      if($this->transmission === "email"){
        $this->error = empty($user[0]['mail']);
      }
      if($this->transmission === "sms") {
        $this->error = empty($user[0]['phone']);
      }
      if($this->transmission === "app") {
        $userHasEnabledTwoFA = Select::find("authentication_app",['uid'=>$user[0]['uid']]);
        if(empty($userHasEnabledTwoFA[0]['secret_keys'])) {
          if(filter_var($this->receiver, FILTER_VALIDATE_EMAIL)) {
            $this->transmission = "email";
            $this->error = false;
          }else{
            $this->error = true;
          }
        }else{
          $this->error = false;
        }
      }
    }

    if(!empty($user[0]['uid'])) {
      $this->user = $user;
    }
  }

  public function sendOtp(): bool {

    if(!empty($this->user)) {

      if($this->transmission === "email") {
        $reciever = new Reciever(
          [
            $this->user[0]['mail']
          ],
          [
            $this->user[0]['firstname']. " ". $this->user[0]['lastname']
          ]);
        $otp = User::load($this->user[0]['uid'])->otp();
        $this->key = $otp['key'];
        $params = [
          "subject" => "Attempt Login OTP",
          "message" => "You OTP code: {$otp['otp']}"
        ];
        if(Mail::send($reciever,$params)){
          $routes = new Routes();
          $routes = $routes->getRoutesCollection();
          if(!empty($routes)) {
            foreach ($routes as $route){
              if($route['controller']['id'] === $this->callBackControllerId){
                $this->callBackRoute = $route;
                break;
              }
            }
          }
        }
        return (!empty($this->callBackRoute));
      }

      //TODO for app and sms.
    }
    return false;
  }

  public function setCallback(string $controllerId): void {
    $this->callBackControllerId = $controllerId;
  }
}