<?php

namespace Innova\Controller\routers\installation\src;

use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class InstallationController
{
    public function installation(): mixed
    {
        $data['url'] = (new Request())->httpSchema(). "/database/create";
        return TemplatesHandler::view("installation/installation.php",$data, isDefaultView: true);
    }
}