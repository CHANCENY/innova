<?php
namespace Innova\Controller\routers\settings\src;


use Innova\Configs\ConfigHandler;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class EmailSettings
{

    public function page(): mixed {
        $this->changeSettings(new Request());
        $this->saveSettings(new Request());
        $data['config'] = ConfigHandler::config("email_settings");
        $data['host'] = (new Request())->httpSchema() . '/settings/email';
        return TemplatesHandler::view("settings/email_setting.php", $data ,isDefaultView: true);
    }

    private function saveSettings(Request $param): void
    {
        if($param->method() === "post"){
            $data['email'] = $param->post("email");
            $data['password'] = $param->post("password");
            $data['user'] = $param->post("user");
            $data['port'] = $param->post("port");
            $data['smtp'] = $param->post('smtp');
            $data['name'] = $param->post("name");
            $data['reply'] = $param->post("reply");

            $old = ConfigHandler::config("email_settings");
            $old[] = $data;
            ConfigHandler::createBackUp("email_settings");
            ConfigHandler::configRemove("email_settings");
            if(ConfigHandler::create("email_settings", $old)){
                Messager::message()->addMessage("Saved Email settings");
            }
        }
    }

    private function changeSettings(Request $param)
    {
        if(!empty($param->get("action")) && !empty($param->get("key"))){

            $config = ConfigHandler::config("email_settings");
            if(!empty($config)){
                $k = intval($param->get("key"));
                foreach ($config as $key=>$value) {
                    if($k === $key) {
                        $value['active'] = $param->get("action") === "active";
                        ConfigHandler::createBackUp("email_settings");
                        ConfigHandler::configRemove("email_settings");
                        $config[$key] = $value;
                        if(ConfigHandler::create("email_settings", $config)){
                            Messager::message()->addMessage("Updated settings");
                            $param->redirection("/settings/email");
                        }
                        break;
                    }
                }
            }
        }
    }

}