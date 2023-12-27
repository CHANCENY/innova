<?php
namespace Innova\Controller\routers\settings\src;


use Innova\Configs\ConfigHandler;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class ErrorHandlingSetting
{
    public function page(): mixed
    {
        $this->saveSettings();
        $data['setting'] = ConfigHandler::config("error_setting");
        return TemplatesHandler::view('settings/errors.php', $data,isDefaultView: true);
    }

    private function saveSettings()
    {
        $req = new Request();
        if($req->method() === "post")
        {
            $data['enabled'] = $req->post('error_handle');
            if(ConfigHandler::create('error_setting',$data))
            {
                Messager::message()->addMessage("Error handle setting saved");
            }else{
                Messager::message()->addWarning("Failed to save error handling setting");
            }
        }
    }
}