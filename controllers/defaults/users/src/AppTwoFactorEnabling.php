<?php
namespace Innova\Controller\routers\users\src;


use Innova\Databases\Update;
use Innova\Middlewares\AppAuthentication;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class AppTwoFactorEnabling
{
  public function page(): mixed {
    $request = new Request();
    $uid = $request->get("uid");
    $barCode = null;
    if($request->method() === "get") {
      $app = new AppAuthentication();
      $app->enablingUser(intval($uid));
      $barCode = $app->getPath();
    }
    if($request->method() === 'post') {
      if($this->enablingAppVerification(new Request())) {
        Messager::message()->addMessage("You have Enabled 2FA successfully");
      }
      else{
        Messager::message()->addError("Something went wrong please check your code.");
      }
    }
    return TemplatesHandler::view(
      "users/enabling_app.php",
      [
        'image'=>$barCode,
        'url' => $request->currentURI()
      ],
      TRUE);
  }

  private function enablingAppVerification(Request $param): bool {
    $code = $param->post("code");

    if(is_numeric($code)) {
      $app = new AppAuthentication();
      if($app->verificationUserCode($code,intval($param->get('uid'))))
      {
        return Update::update(
          "authentication_app",
          [
            'verified'=>1,
          ],
          [
            'uid'=>$param->get('uid')
          ]
        );
      }
      return false;
    }
  }

}