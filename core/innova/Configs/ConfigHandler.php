<?php

namespace Innova\Configs;

class ConfigHandler
{
    public static function config(string $configName): mixed
    {
        return (new static())->find($configName);
    }

    public function find(string $configName): mixed
    {
        $config = "sites/settings/configs/$configName.json";
        if(file_exists($config))
        {
            return json_decode(file_get_contents($config), true) ?? [];
        }
        return null;
    }

    public function store(string $configName, array $value): bool
    {
        $config = "sites/settings/configs/$configName.json";
        if(file_exists($config))
        {
            $content = json_decode(file_get_contents($config), true);
            if($configName === "config_routes")
            {
                $content[] = $value;
            }else{
                $content = array_merge($content, $value);
            }
            return !empty(file_put_contents($config, $this->formatter(json_encode($content, JSON_PRETTY_PRINT))));
        }
        return !empty(file_put_contents($config, $this->formatter(json_encode($value, JSON_PRETTY_PRINT))));
    }

    public static function create(string $configName, array $value): bool
    {
        return (new static())->store($configName, $value);
    }

    public function formatter(string $content): string
    {
        return str_replace(["\/", "\/\/"], ["/", "//"], $content);
    }

    public function deleteConfig(string $configName): bool
    {
        $config = "sites/settings/configs/$configName.json";
        if(file_exists($config))
        {
            return unlink($config);
        }
        return false;
    }

    public static function configRemove(string $configName): bool
    {
        return (new static())->deleteConfig($configName);
    }

    public function backUp(string $configName): bool
    {
        $config = "sites/settings/configs/backups";
        if(!is_dir($config))
        {
            mkdir($config, 0777);
            chmod($config, 0777);
        }
        $config = "sites/settings/configs/backups/$configName.json";
        if(!file_exists($configName))
        {
            file_put_contents($config, '');
        }

        $data = ConfigHandler::config($configName);
        if(!empty($data))
        {
            return !empty(file_put_contents($config, $this->formatter(json_encode($data,JSON_PRETTY_PRINT))));
        }
        return false;
    }

    public static function createBackUp(string $configName): bool
    {
        return (new static())->backUp($configName);
    }

}