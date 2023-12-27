<?php
namespace Innova\Controller\routers\settings\src;


use Innova\Configs\ConfigHandler;
use Innova\Databases\Tables;
use Innova\modules\Messager;
use Innova\modules\StorageDefinition;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class StoragesUpdate
{
    public function page(): mixed {
        $data['setting'] = ConfigHandler::config("storage_update");
        $this->settingsSave();
        return TemplatesHandler::view("settings/storage_update.php",$data ,isDefaultView: true);
    }

    private function settingsSave()
    {
        $req = new Request();
        if($req->method() === 'post')
        {
            $data['enabled'] = $req->post("storage_update");
            ConfigHandler::createBackUp('storage_update');
            ConfigHandler::configRemove('storage_update');
            if(ConfigHandler::create('storage_update',$data))
            {
                $d = ConfigHandler::config("storage_update");
                if($d['enabled'] === "on")
                {
                    Tables::installTables();
                }
                Messager::message()->addMessage("Storage auto update setting saved");
            }else{
                Messager::message()->addError("Failed to save settings");
            }
        }
    }
}