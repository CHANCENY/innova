<?php
namespace Innova\Controller\routers\settings\src;

use Innova\Configs\ConfigHandler;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class FilesSettings
{
    public function page(): mixed {
        if($this->saveSettings()){
            Messager::message()->addMessage("Files Settings save successfully");
        }
        $file_settings = ConfigHandler::config("files_settings");
        return TemplatesHandler::view("settings/files_settings.php", $file_settings, true);
    }

    private function saveSettings(): bool
    {
        $request = new Request();
        if($request->method() === "post") {
            $data['upload_max_filesize'] = $request->post("upload_max_filesize");
            $data['post_max_size'] = $request->post("post_max_size" );
            $data['max_file_uploads'] = $request->post("max_file_uploads" );
            $data['upload_tmp_dir'] = $request->post("upload_tmp_dir" );
            $data['max_input_time'] = $request->post("max_input_time" );
            $data['max_execution_time'] = $request->post("max_execution_time" );

            ConfigHandler::createBackUp("files_settings");
            ConfigHandler::configRemove("files_settings");
            return ConfigHandler::create("files_settings", $data);
        }
        return  false;
    }
}