<?php
namespace Innova\Controller\routers\extends\src;

use Innova\Entities\FileSystem;
use Innova\modules\Files;
use Innova\modules\Installer;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class ModuleInstaller
{
    public function page(): mixed {
        $this->moduleInstall();
        return TemplatesHandler::view(
            "extends/module_installer.php",
            isDefaultView: true
        );
    }

    private function moduleInstall(): void
    {
        $request = new Request();
        if($request->method() === "post")
        {
            $file = new Files();
            $file->uploadFromForm("module");
            $fid = $file->finishUpload(3);

            if(is_numeric($fid)) {
                $fileEntity = FileSystem::load($fid);
                $path = $fileEntity->getPath();

                //installing
                $installer = new Installer($path);
                if($installer->installModule()) {
                    Messager::message()->addMessage("Module added successfully");
                }else{
                    Messager::message()->addError("Failed to add your module");
                }
            }
        }
    }

}