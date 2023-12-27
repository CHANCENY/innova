<?php

namespace Innova\Controller\routers\routing\src;

use Innova\Configs\ConfigHandler;
use Innova\modules\Messager;
use Innova\modules\RouteConfiguration;
use Innova\modules\Routing;
use Innova\modules\StorageDefinition;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class RoutingCreateController extends Request
{
    public function routingCreationForm(): mixed
    {
        if($this->method() === "post" && !empty($this->post("create_reoute")))
        {
            $data = [
                "route_name"=> $this->post("route_name"),
                "route_uri" => $this->post("route_uri"),
                "access" =>  $_POST['access'],
                "module_path" => $this->post("module_path"),
                "database_connect" => $this->post("database_connect"),
                "database_tables_allowed" =>  $_POST['database_tables_allowed'],
                "allowed_query" =>$_POST['allowed_query'],
                "method" => $_POST['method']
            ];
            $keys = [];
            $options =[];
            $types = [];
            for ($i = 0; $i < 20; $i++)
            {
                if(!empty($_POST["param_key_$i"]))
                {
                    $keys[] = $_POST["param_key_$i"];
                    $options[] = $_POST["params_option_$i"];
                    $types[] = $_POST["params_type_$i"];
                }
            }
            $data['params']['keys'] = $keys;
            $data['params']['options'] = $options;
            $data['params']['types'] = $types;

            $route = new RouteConfiguration();
            $route->createURLConfig($data);
            $route->buildController($data);
            if($route->build())
            {
                Messager::message()->addMessage("Route added successfully 
                 Visit new route <a href='@link'>@name</a>",['@link'=> (new Request())->post("route_uri"),
                '@name' => (new Request())->post("route_name")
                ]);

                (new Request())->redirection('/');
            }
            Messager::message()->addError("Failed to create Route");
        }

        $routing = new Routing();
        $data['action'] = $this->httpSchema() . "/routing/create";
        $data['uris'] = (new ConfigHandler())->formatter(json_encode($routing->getRouteUris()));
        $data['storages'] = StorageDefinition::storageNames();
        return TemplatesHandler::view("routing/route_creation_form.php",$data, isDefaultView: true);
    }
}