<?php
namespace Innova\Controller\routers\users\src;

use Innova\Entities\User;
use Innova\Middlewares\Auth;
use Innova\modules\CurrentUser;
use Innova\modules\Messager;
use Innova\modules\Site;
use Innova\request\Request;
use Innova\Sessions\Session;
use Innova\Templates\TemplatesHandler;

class OTPVerification
{
  private Auth $authMethod;

  public function __construct()
  {
    $this->authMethod = new Auth();
  }
  public function page(): mixed {
    $data = [
      'logo'=> Site::logo(),
      "msg"=>null,
      'current' => (new Request())->currentURI()
    ];
    if($this->verificationCode(new Request()))
    {
      (new Request())->redirection("/");
    }
    return TemplatesHandler::view("user_login/otp_verification.php",$data, isDefaultView: true);
  }

  private function verificationCode(Request $param): bool {
    if($param->method() === "post")
    {
      $key = $param->get("otp_key");
      $user = Session::get("otp_time,$key");
      if(!empty($user)) {
        $otp = $user['otp'] ?? null;
        $time = $user['time'] ?? null;
        $uid = $user['uid'] ?? null;
        $transmission = $this->authMethod->getTransmission();
        if($transmission === "email" || $transmission === "sms")
        {
          if(is_numeric($otp) && is_numeric($time) && is_numeric($uid)) {
            $now = time();
            if(($now - $time) < 300 && $param->post("otp") === $otp) {
              $user = User::load($uid);
              if(!empty($user->id())){
                Session::clear("otp_time,$key");
                return (new CurrentUser())->setCurrentUser($user->id());
              }
            }
          }
        }
        Session::clear("otp_time,$key");
        Messager::message()->addError("2FA Code verification failed.");
      }
    }
    return false;
  }
}