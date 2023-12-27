<?php
namespace Innova\Controller\routers\settings\src;


use Innova\Configs\ConfigHandler;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class HomeSetting
{

  public function page(): mixed {
    if($this->homeSetting(new Request())){
      Messager::message()->addMessage("Home controller saved");
    }
    return TemplatesHandler::view("settings/home_settings.php", isDefaultView: TRUE);
  }

  private function homeSetting(Request $param) {
    if($param->method() === "post"){
      $data = ['home'=> $param->post("home")];
      ConfigHandler::createBackUp("home_page");
      ConfigHandler::configRemove("home_page");
      return ConfigHandler::create("home_page", $data);
    }
  }

}