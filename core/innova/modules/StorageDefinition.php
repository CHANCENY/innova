<?php

namespace Innova\modules;

use Innova\Configs\ConfigHandler;
use Innova\Databases\Query;
use Innova\Databases\Tables;
use Innova\Exceptions\MissingFieldException;

class StorageDefinition
{
    private mixed $routes;

    /**
     * @return mixed
     */
    public function getRoutes(): mixed
    {
        return $this->routes;
    }

    /**
     * @return array
     */
    public function getStorages(): array
    {
        return $this->storages;
    }


    private array $storages;

    public function __construct()
    {
        $this->routes = ConfigHandler::config("system_storages");
    }

    public function storages(): StorageDefinition
    {
        foreach ($this->routes as $key=>$value)
        {
            $this->storages[] = $value['table'] ?? null;
        }
        return $this;
    }

    public static function storageNames(): array
    {
        return (new static())->storages()->storages;
    }

    public function addDefinition(array $data): bool
    {
        if(!empty($data))
        {
            $exe = ['table', 'columns', 'indexes', 'install'];
            $keys = array_keys($data);
            foreach ($keys as $key)
            {
                if(!in_array($key, $exe))
                {
                    throw new MissingFieldException("$key is missing in your storage definition");
                }
            }

            $this->routes[] = $data;
          //  ConfigHandler::createBackUp("system_storages");
            ConfigHandler::configRemove("system_storages");
            if(ConfigHandler::create("system_storages", $this->routes))
            {
                $d = ConfigHandler::config("storage_update");
                if(!empty($d['enabled']) && $d['enabled'] === "on")
                {
                    return Tables::installTables();
                }
                return true;
            }
        }
        return false;
    }


    public function storage(string $storageName = null): StorageDefinition
    {
        if(!empty($storageName))
        {
            $data = [];
            $all = $this->routes;
            foreach ($all as $key => $route)
            {
                if(str_contains($route['table'], $storageName))
                {
                    unset($this->routes[$key]);
                    $data[] = $route;
                }
            }
            $this->routes = $data;
        }
        $this->storages();
        return $this;
    }


    public function deleteDefinition(string $name):bool
    {
        $name = strtolower(str_replace(" ", "_", $name));
        $data = [];
        foreach ($this->routes as $key=>$value)
        {
            if(!empty($value['table']) && $name !== $value['table'])
            {
                $data[] = $value;
            }
        }

        ConfigHandler::createBackUp("system_storages");
        ConfigHandler::configRemove("system_storages");
        if(ConfigHandler::create("system_storages", $data))
        {
          try {
            $controller = active_controller();
            $controller->setTables($name);
            $controller->setAllowed("DROP");
            Query::query("DROP TABLE $name")->queryRunner();
          }catch (\Throwable $exception) {

          }
            return true;
        }
        return false;
    }

}