<?php
namespace Innova\Controller\routers\users\src;

use Innova\Middlewares\AppAuthentication;
use Innova\modules\CurrentUser;
use Innova\modules\Messager;
use Innova\modules\Site;
use Innova\request\Request;
use Innova\Sessions\Session;
use Innova\Templates\TemplatesHandler;

class AuthenticationApp
{

  public function page(): mixed {
    $req = new Request();
    $this->verifyTwoFACode($req);
    return TemplatesHandler::view(
      "users/app_auth_code.php",
      [
        "url" => $req->currentURI(),
        "logo" => Site::logo()
      ],
      true
    );
  }

  private function verifyTwoFACode(Request $req): void {
    if($req->method() === "post") {

      $app = new AppAuthentication();
      $user = Session::get("otp_time,{$req->get('session_id')}");
      if(!empty($user)) {
        $time = $user['time'];
        $now = time();
        if($now - $time < 300) {
          $uid = $user['uid'];
          if($app->verificationUserCode($req->post("code"),$uid)) {
            $current = new CurrentUser();
            $current->setCurrentUser($uid);

            $user = new CurrentUser();
            if(!empty($user->id())) {
              Session::clear("otp_time,{$req->get('session_id')}");
              $req->redirection("/");
            }
          }
        }
      }
      Messager::message()->addError("Something went wrong we couldn't verify your code.");
    }
  }

}