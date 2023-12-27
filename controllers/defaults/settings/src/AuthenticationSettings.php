<?php
namespace Innova\Controller\routers\settings\src;


use Innova\Configs\ConfigHandler;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class AuthenticationSettings
{

  public function page(): mixed {
    $results = $this->authenticationSettings(new Request());
    if($results === true) {
      Messager::message()->addMessage("Successfully added authentication settings.");
    }
    return TemplatesHandler::view("settings/authentication.php" ,isDefaultView: true);
  }
  private function authenticationSettings(Request $request) {

    if($request->method() === "post") {
      $data['normal'] = [
        'view' => $request->post("normal") ?? "user_login/form.php",
        'isDefault' => empty($request->post("normal")),
        'active' => $request->post("active") === "normal"
      ];

      $data['password_less'] = [
        'view' => $request->post("password_less") ?? "user_login/password_less.php",
        'isDefault' => empty($request->post("password_less")),
        'active' =>$request->post("active") === "password_less"
      ];

      $data['2fa'] = [
        'view' => $request->post("2fa") ?? "user_login/auth_2fa.php",
        'isDefault' => empty($request->post("2fa")),
        'active' => $request->post("active") === "2fa"
      ];

      if(!empty($request->post("transmission"))) {
        $data['2fa']['transmission'] = $request->post("transmission");
      }
      ConfigHandler::configRemove("authentications");
      return ConfigHandler::create("authentications", $data);
    }
    return false;
  }

}