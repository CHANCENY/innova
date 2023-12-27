<?php
namespace Innova\Controller\routers\routing\src;

use Innova\modules\Routing;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class RoutesListingBoard extends Routing
{
    public function page(): mixed
    {
        $routes['routes'] = $this->getRouteCollection();
        $routes['host'] = (new Request())->httpSchema();
        return TemplatesHandler::view('routing/routes_listing.php',$routes, isDefaultView: true);
    }
}