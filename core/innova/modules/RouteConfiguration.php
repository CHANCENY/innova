<?php

namespace Innova\modules;

use Innova\Configs\ConfigHandler;
use Innova\Routes\Routes;
use Ramsey\Uuid\Uuid;

class RouteConfiguration
{

    public \Exception $error;

    private array $routeConfig;

    /**
     * @return \Exception
     */
    public function getError(): \Exception
    {
        return $this->error;
    }

    /**
     * @return array
     */
    public function getRouteConfig(): array
    {
        return $this->routeConfig;
    }

    public function createURLConfig(array $data): RouteConfiguration
    {
        try {
            $required = ['route_name','route_uri', 'params'];
            $keys = array_keys($data);
            foreach ($required as $key=>$value)
            {
                if(!in_array($value, $keys))
                {
                    throw new \Exception("Keys not found $value");
                }
                if($value === "route_uri")
                {
                    $this->routeConfig[$value] = trim($data[$value], "/");
                }
                else
                {
                    $this->routeConfig[$value] = $data[$value];
                }
            }
            $this->createParameters($data['params'] ?? []);
        }catch (\Exception $exception)
        {
            $this->error = $exception;
        }
        return $this;
    }

    public function regexs($type): string
    {
         return match ($type)
         {
             "string"=> "([^/]+)",
             "numerical" => "(\d+)"
         };
    }


    public function createParameters(array $data): RouteConfiguration
    {
        $parameters = null;
        $options = [];
        $paramsKeys = [];
        if(!empty($data))
        {
            foreach ($data['keys'] as $key=>$value)
            {
                $parameters .= "/". $this->regexs($data['types'][$key]) . "/";
                $options[$value] = $data['options'][$key] ?? "optional";
                $paramsKeys[] = $value;
            }
            if(!empty($parameters))
            {
                $parameters = trim($parameters, "/");
                $parameters = str_replace('//', '/', $parameters);
                $parameters = $parameters . "$";
            }
        }

        $uri = $this->routeConfig['route_uri'] ?? null;
        $completeURI = null;
        if(!is_null($parameters))
        {
            $completeURI = str_ends_with($uri, "/") ? $uri. $parameters : $uri . "/" . $parameters;
        }
        else
        {
            $completeURI = $this->routeConfig['route_uri'];
        }
        $this->routeConfig['params'] = [
            "keys"=>$paramsKeys,
            "options"=>$options
        ];

        $this->routeConfig['route_uri'] = "/". $completeURI;
        return $this;
    }


    public function buildController(array $data): RouteConfiguration
    {
        $controller = &$this->routeConfig['controller'];
        if(empty($data['access']))
        {
            throw new \Exception("Access of controller is not set");
        }
        $controller['access'] = $data['access'];

        if(empty($data['allowed_query']))
        {
            throw new \Exception("Database allowed queries configuration not set");
        }
        $controller['database']['allowed_query'] = $data['allowed_query'];

        if(empty($data['database_connect']))
        {
            $data['database_connect'] = false;
        }
        $controller['database']['database_connect'] = $data['database_connect'];

        if(empty($data['database_tables_allowed']))
        {
            throw new \Exception("Database table to be access not set");
        }
        $controller['database']['database_tables_allowed'] = $data['database_tables_allowed'];

        if(empty($data['module_path']))
        {
            throw new \Exception("Controller module path not set");
        }
        $controller['module_path'] = "controllers/".$data['module_path'];

        $list = explode("/", $data['module_path']);
        $modulename = end($list);
        if(str_starts_with($data['module_path'], "defaults"))
        {
            $controller['namespace'] = "Innova\\Controller\\routers\\$modulename\\src";
        }else{
            $controller['namespace'] = "Innova\\Controller\\Custom\\$modulename\\src";
        }

        if(empty($data['method']))
        {
            throw new \Exception("Access Methods not set");
        }
        $this->routeConfig['headers']['method'] = $data['method'];
        $this->routeConfig['headers']['content-type'] = "text/html";
        $this->routeConfig['headers']['cors'] = "allowed";
        return $this;
    }


    public function build(): bool
    {
        //create controller
        $pathModule = $this->routeConfig['controller']['module_path'];
        $routeName = $this->routeConfig['route_name'];
        $namespace = $this->routeConfig['controller']['namespace'];
        $list = explode(" ", $routeName);
        foreach ($list as &$value) {
            $value = ucfirst($value);
        }
        $className = implode("",$list);
        $list = explode("/", $pathModule);
        $location = end($list);

        if(!is_dir($pathModule))
        {
            mkdir($pathModule, 0777);
        }

        $infoFile = $pathModule . "/" . $location. ".info.json";
        $library = $pathModule . "/" . $location . ".libraries.json";
        $fun = $pathModule . "/" . "functions.php";

        if(!file_exists($infoFile))
        {
            file_put_contents($infoFile, json_encode([]));
        }

        if(!file_exists($library))
        {
            file_put_contents($library, json_encode([]));
        }

        if(!file_exists($fun))
        {
            file_put_contents($fun, "<?php");
        }

        $source = $pathModule . "/src";
        if(!is_dir($source))
        {
           mkdir($source);
        }

        $function = "public function page(): mixed { return 'Welcome $routeName';}";
        $classFile = str_replace(" ", "", $pathModule . "/" . "src/" . $className . ".php");
        if(!file_exists($classFile))
        {
            $data = PHP_EOL. "namespace $namespace; ". PHP_EOL;
            $data .= PHP_EOL. PHP_EOL . "class $className ". PHP_EOL . "{ ". PHP_EOL . PHP_EOL . PHP_EOL . $function . PHP_EOL .PHP_EOL .PHP_EOL. "}";
            file_put_contents($classFile, "<?php $data");
        }

        $moduleID = Uuid::uuid4()->toString();

        $control = json_decode(file_get_contents($infoFile), true);
        $control[] = [
            "controller"=> $className,
            "entry_point" => "page",
            "id" => $moduleID,
        ];
        $libraries = json_decode(file_get_contents($library), true);
        $libraries[] = [
            "id"=>$moduleID,
            "head"=>[
                "css"=>[],
                "js"=>[]
            ],
            "footer"=>[
                "css"=>[],
                "js"=>[]
            ],
        ];

        file_put_contents($infoFile, (new ConfigHandler())->formatter(json_encode($control,JSON_PRETTY_PRINT)));
        file_put_contents($library, (new ConfigHandler())->formatter(json_encode($libraries,JSON_PRETTY_PRINT)));

        $controllers = ConfigHandler::config("config_routes");
        $this->routeConfig["controller"]['id'] = $moduleID;
        $controllers[] = $this->routeConfig;
        ConfigHandler::configRemove("config_routes");
        if(ConfigHandler::create("config_routes", $controllers))
        {
            return true;
        }
        return false;
    }
}