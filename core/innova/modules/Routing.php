<?php

namespace Innova\modules;

use Innova\Configs\ConfigHandler;

class Routing
{
    private mixed $routeCollection;
    private array $routeIDs;

    /**
     * @return mixed
     */
    public function getRouteCollection(): mixed
    {
        return $this->routeCollection;
    }

    /**
     * @return array
     */
    public function getRouteIDs(): array
    {
        return $this->routeIDs;
    }

    /**
     * @return array
     */
    public function getRouteNames(): array
    {
        return $this->routeNames;
    }

    /**
     * @return array
     */
    public function getRouteUris(): array
    {
        return $this->route_uris;
    }
    private  array $routeNames;
    private array $route_uris;

    public function __construct()
    {
        $this->routeCollection = ConfigHandler::config("config_routes");
        if(!empty($this->routeCollection))
        {
            foreach ($this->routeCollection as $key=>$value)
            {
                $this->routeIDs[] = $value['controller']['id'] ?? null;
                $this->routeNames[] = $value['route_name'] ?? null;
                $this->route_uris[] = $value['route_uri'] ?? null;
            }
        }
    }

    public function updateRoute($data): bool
    {
        ConfigHandler::createBackUp("config_routes");
        ConfigHandler::configRemove('config_routes');
        return ConfigHandler::create("config_routes",$data);
    }
}