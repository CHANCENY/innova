<?php
namespace Innova\Controller\routers\routing\src;


use Innova\modules\Messager;
use Innova\modules\Routing;
use Innova\modules\StorageDefinition;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class RoutingEdit extends Routing
{
    public function page(): mixed
    {
        $req = new Request();
        $routeID = $req->get('id');
        $routeName = $req->get('name');
        $routes = $this->getRouteCollection();
        $data['ob'] = $this;
        $data['name'] = str_replace('-', ' ', $routeName);
        $data['tables'] = (new StorageDefinition())->storages()->getStorages();
        foreach ($routes as $key=>$route)
        {
            if(!empty($route['controller']['id']) && $route['controller']['id'] === $routeID)
            {
                $data['route'] = $route;
                break;
            }
        }
        $this->updateRouteInformation();
        return TemplatesHandler::view("routing/routing_edit.php",$data , isDefaultView: true);
    }

    public function isTableAllowed(array $routeT, string $table): bool
    {
        return in_array($table, $routeT);
    }

    private function updateRouteInformation()
    {
        $req = new Request();
        $id = $req->get('id');
        if($req->method() === 'post')
        {
            $routes = $this->getRouteCollection();
            $flag = false;
            foreach ($routes as $key=>$route)
            {
                if(
                    !empty($route['controller']['id']) &&
                    $id === $route['controller']['id']
                ){
                    $route['route_name'] = $req->post('route_name', false) ?? $route['route_name'];
                    $route['route_uri'] = $req->post('route_uri', false) ?? $route['route_uri'];
                    $route['headers']['method'] = $_POST['method'] ?? $route['headers']['method'];
                    $route['controller']['access'] = $_POST['access'] ?? $route['controller']['access'];
                    $route['controller']['database']['allowed_query'] = $_POST['allowed_query'] ?? $route['controller']['database']['allowed_query'];
                    $route['controller']['database']['database_tables_allowed'] = $_POST['database_tables_allowed'] ?? $route['controller']['database']['database_tables_allowed'];
                    $routes[$key] = $route;
                    $flag = true;
                    break;
                }
            }

            if($flag === true && $this->updateRoute($routes))
            {
                Messager::message()->addMessage("Route Updated successfully");
            }else{
                Messager::message()->addError("Route Update failed");
            }
        }
    }
}