<?php
namespace Innova\Controller\routers\settings\src;


use Innova\Configs\ConfigHandler;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class FormSettings
{

    public function page(): mixed {
        $this->saveSettings();
        $data['setting'] = ConfigHandler::config("forms_settings");
        return TemplatesHandler::view("settings/forms_settings.php",$data,isDefaultView: true);
    }

    private function saveSettings(): void
    {
        $req = new Request();
        if($req->method() === "post")
        {
            $data['enabled'] = $req->post('csrf_handler');

            ConfigHandler::configRemove("forms_settings");

            if(ConfigHandler::create('forms_settings',$data))
            {
                Messager::message()->addMessage("CSRF handle setting saved");
            }else{
                Messager::message()->addWarning("Failed to save CSRF handling setting");
            }
        }
    }

}