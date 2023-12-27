<?php

namespace Innova\modules;

use Innova\Configs\ConfigHandler;
use Innova\Exceptions\InstallException;
use Ramsey\Uuid\Uuid;

class Installer {

    public function __construct(
        private string $zip,
        private bool $isModule = true,
        private bool $isLibrary = false
    ) {
        if($this->isModule === $this->isLibrary) {
            throw new InstallException("Installation type conflict. do you want to install module or library");
        }
    }

    /**
     * @return bool
     * @throws InstallException
     * @throws \Innova\Exceptions\ZipperException
     * @throws \Random\RandomException
     */
    public function installModule(): bool
    {
        if(!file_exists($this->zip)) {
            throw new InstallException("Zip path provided not found");
        }

        $temporary = "sites/files/temp/". random_int(0, 900);
        if(!is_dir($temporary)) {
            mkdir($temporary);
        }

        up:
        $moduleName = $temporary. "/module_" . random_int(0, 900);
        if(is_dir($moduleName)) {
            goto up;
        }

        mkdir($moduleName  );

        // Extract zip file and check for requirements.
        if(!Zipper::extract($this->zip,$moduleName)) {
            throw new InstallException("Failed to unzip your module in $moduleName");
        }

        $listContent = array_diff(scandir($moduleName),['.', '..', '...']);

        //requirement
        //module requires src/
        //namespace always will start with modules/custom/module-name/src/php files
        //on root will need module-name.info.json file containing
        // {"name": "module-name", "description": "module-description"}

        //module structure
        //module-name/src/
        //module-name/module-name.info.json

        //looking for module.json
        $infofile = null;
        foreach ($listContent as $f){
            if(str_ends_with($f, ".info.json")){
                $infofile = $f;
            }
        }
        $moduleJson = $moduleName . "/" . $infofile;
        if(!file_exists($moduleJson) || !str_ends_with($moduleJson, ".info.json")){
            throw new InstallException(".info.json file not found at root of your module");
        }

        $content = json_decode(file_get_contents($moduleJson ), true);
        $moduleRoot = $content['name'] ?? null;
        if(empty($moduleRoot) || strpos($moduleRoot, " ")){
            throw new InstallException("Check your module name either not found or name has space");
        }

        $modulePath = "modules/custom/$moduleRoot";
        if(is_dir($modulePath)) {
            throw new InstallException("Module will this name ($moduleRoot) already exist");
        }

        mkdir($modulePath);

        $source = $moduleName . "/src";
        if(!is_dir($source)) {
            throw new InstallException("Module src folder not found");
        }

        if(Zipper::extract($this->zip, $modulePath)){
            $content['module_id'] = Uuid::uuid4()->toString();
            file_put_contents($modulePath . "/$infofile",json_encode($content,JSON_PRETTY_PRINT));
        }

        //zip from temp to all
        if(Files::removeDirectory($moduleName) && is_dir($moduleName)){
            rmdir($temporary);
        }

        if(!$this->recordModule($content)) {
            return false;
        }
        return true;
    }

    /**
     * @param mixed $content
     * @return bool
     */
    private function recordModule(mixed $content): bool
    {
        $old = ConfigHandler::config("extension");
        ConfigHandler::createBackUp("extension");
        $old[] = $content;
        ConfigHandler::configRemove("extension");
        return ConfigHandler::create("extension", $old);
    }

}