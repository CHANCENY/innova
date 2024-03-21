<?php

namespace Innova\Controller\routers\users\src;

use Innova\Controller\routers\settings\src\AuthenticationSettings;
use Innova\Databases\Select;
use Innova\Entities\User;
use Innova\Middlewares\Auth;
use Innova\Middlewares\PassLess;
use Innova\Middlewares\TwoAuth;
use Innova\modules\CurrentUser;
use Innova\modules\Forms;
use Innova\modules\Messager;
use Innova\modules\Site;
use Innova\request\Request;
use Innova\Sessions\Session;
use Innova\Templates\TemplatesHandler;

class UserLoginController
{
  private Auth $authMethod;
  private string $view;
  private mixed $default;

  public function __construct()
  {
    $this->authMethod = new Auth();
    $this->view = $this->authMethod->getViewTemplate();
    $this->default = $this->authMethod->getIsDefault();
  }

  public function userLoginForm(): mixed
  {
    $data = [
      'logo'=> Site::logo(),
      "msg"=>null
    ];

    if((new Request())->method() === "post" && $this->authMethod->getAuthenticationMethod() === "normal")
    {
      if($this->loginUser(new Request()))
      {
        $currentUser = new CurrentUser();
        $firstname = $currentUser->firstname();
        Messager::message()->addMessage("Welcome $firstname to @site", ['@site'=>Site::name()]);
        (new Request())->redirection('/user/'. $currentUser->id(), true);
      }else{
        $data['msg'] = <<<ALERT
<div class="alert alert-warning alert-dismissible fade show" role="alert">
								Username or EmailAddress or Password is invalid. 
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
ALERT;
      }
    }

    if((new Request())->method() === "post" && $this->authMethod->getAuthenticationMethod() === "password_less")
    {
      $this->loginUser(new Request());
    }

    if((new Request())->method() === "post" && $this->authMethod->getAuthenticationMethod() === "2fa")
    {
      if($this->loginUser(new Request())) {
        $currentUser = new CurrentUser();
        (new Request())->redirection("/user/". $currentUser->id());
      }
    }

    return TemplatesHandler::view($this->view, $data, isDefaultView: $this->default);
  }

  private function loginUser(Request $request): bool
  {
    Forms::login($request->post("username"), $request->post("password"));
    $currentUser = new CurrentUser();
    if(!empty($currentUser->id()))
    {
      if($this->authMethod->getAuthenticationMethod() === "2fa") {
        $control = active_controller();
        $control->setTables("authentication_app");
        $set = Select::find("authentication_app",['uid'=>$currentUser->id(),'verified'=>1]);
        if(empty($set[0]['uid'])) {
          $this->authMethod->setAuthenticationMethod();
          $this->authMethod->setTransmission("email");
          $this->authMethod->setAuthenticationView("user_login/form.php");
        }
      }
    }

    Session::clear("user", "private");

    if($this->authMethod->getAuthenticationMethod() === "normal") {
      return Forms::login($request->post("username"), $request->post("password"));
    }

    if($this->authMethod->getAuthenticationMethod() === "password_less") {
      $passwordLess = new PassLess();
      $passwordLess->setCallBackController("87d8911a-7fde-443d-a6b0-a1b0b25afee9");
      $passwordLess->setUserEmailAddress($request->post("username"));
      $passwordLess->executeAuthentication();
      Messager::message()->addMessage("We have sent email to your email with further instruction.");
      if($passwordLess->isError() === false) {
        return $passwordLess->sendAuthenticationToken();
      }
    }

    if($this->authMethod->getAuthenticationMethod() === "2fa") {

      if(Forms::login($request->post("username"), $request->post("password")))
      {
        //check transmission
        if($this->authMethod->getTransmission() === "email") {
          Session::clear("user", "private");
          $authOtp = new TwoAuth();
          $authOtp->setOtpReceiver($request->post("username"));
          $authOtp->setCallback("1e172e1d-b1e6-4a6e-b950-9bbd3217886b");
          $authOtp->setTransmission($this->authMethod->getTransmission());
          $authOtp->verifyReceiver();
          if($authOtp->sendOtp())
          {
            if(!$authOtp->isError())
            {
              $callback = $authOtp->getCallBackRoute();
              $list = explode("(", $callback['route_uri']);
              $url = implode("/", array_slice($list,0, count($list) - 1));
              $url = trim($url, "/") . "/" .$authOtp->getKey();
              $request->redirection("/". $url);
            }
          }
        }
        elseif ($this->authMethod->getTransmission() === "app") {
          $user = new CurrentUser();
          $user = User::load($user->id());
          Session::clear("user", "private");
          $otp = $user->otp();
          $request->redirection("/session/two/auth/{$otp['key']}");
        }
      }
      else
      {
        Messager::message()->addError("Username or password is invalid");
      }

      Messager::message()->addError("Failed to send OTP code please contact administrator to solve this issue");
    }


    return false;
  }
}