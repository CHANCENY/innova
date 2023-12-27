<?php
namespace Innova\Controller\routers\routing\src;


use Innova\modules\Messager;
use Innova\modules\Routing;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class RoutingDeletion extends Routing
{
    public function page(): mixed {
        $id = (new Request())->get('id');
        $data['route'] = [];
        $data['host'] = (new Request())->httpSchema();
        if(empty($id))
        {
            Messager::message()->addWarning("Route id not found");
            (new Request())->redirection('/');
        }
        $list  = explode('@',$id);
        $end = end($list);
        $data['final'] = $list[0];
        $id = $list[0];

        if($end !== "question")
        {
            $this->deleteRoute($id);
        }else{
            foreach ($this->getRouteCollection() as $key=>$value)
            {
                if(!empty($value['controller']['id']) && $value['controller']['id'] === $id)
                {
                    $data['route'] = $value;
                }
            }   
        }
        return TemplatesHandler::view('routing/routing_delete.php',$data ,isDefaultView: true);
    }

    private function deleteRoute($id)
    {
        $routes = $this->getRouteCollection();
        foreach ($routes as $key=>$value)
        {
            if(!empty($value['controller']['id']) && $value['controller']['id'] === $id)
            {
                unset($routes[$key]);
                break;
            }
        }

        if((new Routing())->updateRoute($routes))
        {
            Messager::message()->addMessage("Route deleted successfully");
            (new Request())->redirection('/');
        }else{
            Messager::message()->addWarning("Failed to delete route");
        }

    }
}