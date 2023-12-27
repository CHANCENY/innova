<?php

namespace Innova\Controller\routers\default_navigation\src;

use Innova\Modifier\Modifier;
use Innova\modules\CurrentUser;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class Navigation
{
    public function navigationBuild(): string
    {
        $data['uid'] = (new CurrentUser())->id();
        $data['host'] = (new Request())->httpSchema();
        $data['menus'] = Modifier::menus();
        return TemplatesHandler::view("navigation/navigation.php", $data,isDefaultView: true);
    }
}