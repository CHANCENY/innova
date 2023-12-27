<?php

namespace Innova\Middlewares;

use Innova\Databases\Select;
use Innova\Entities\User;
use Innova\interfaces\PasswordLessInterface;
use Innova\modules\Site;
use Innova\Routes\Routes;

abstract class PasswordLess implements PasswordLessInterface {

  private string $emailAddress;

  public function getEmailAddress(): string {
    return $this->emailAddress;
  }

  public function getControllerId(): string {
    return $this->controllerId;
  }

  public function isError(): bool {
    return $this->error;
  }

  public function getCallBackRoute(): mixed {
    return $this->callBackRoute;
  }

  public function getUser(): mixed {
    return $this->user;
  }
  private string $controllerId;
  /**
   * @var true
   */
  private bool $error;

  private mixed $callBackRoute;

  private mixed $user;

  public function __construct() {
    $this->error = true;
    $this->emailAddress = "";
    $this->controllerId = "";
  }

  /**
   * {@inheritdoc }
   */
  public function setUserEmailAddress(string $emailAddress): void {
    $this->emailAddress = $emailAddress;
  }

  /**
   * {@inheritdoc }
   */
  public function setCallBackController(string $controllerId): void {
    $this->controllerId = $controllerId;
  }

  /**
   * {@inheritdoc }
   */
  public function executeAuthentication(): void {

    if(!empty($this->controllerId) && !empty($this->emailAddress)) {

      //Verification of callback given by system.
      if(filter_var($this->emailAddress, FILTER_VALIDATE_EMAIL)){
        $routes = new Routes();
        $routes = $routes->getRoutesCollection();
        if(!empty($routes)) {
          foreach ($routes as $route){
            if($route['controller']['id'] === $this->controllerId){
              $this->callBackRoute = $route;
              break;
            }
          }
        }
      }
      if(!empty($this->callBackRoute)) {

        //Verification of user.
        $user = Select::find("users",["mail" => $this->emailAddress]);
        if(!empty($user[0]['uid'])){
          $this->user = $user[0];
        }
      }
      if(!empty($this->user)) {
        $this->error = false;
      }
    }
  }

  /**
   * {@inheritdoc }
   */
  public function sendAuthenticationToken(): bool {

    $receiver = new \Innova\MailManger\Reciever(
      [$this->user['mail']],
      [$this->user['firstname']. ' '. $this->user['lastname']]
    );
    $params = [
      'subject' => Site::name() . " Login Attempt",
      'message' => "You have requested to login to site: ". Site::name(). " if this
      is not you attempting please ignore this link: ". (new User($this->user['uid']))->oneLoginLink().
        "<br><br>Please ignore if not you and visit website and reset your password."
    ];
   return \Innova\MailManger\Mail::send($receiver, $params);
  }
}