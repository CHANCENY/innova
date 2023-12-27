<?php

namespace Innova\Controller\routers\errors\src;

use Innova\Templates\TemplatesHandler;

class ErrorsController
{
    public function errors(): mixed
    {
        $route = explode("/",$_SERVER['REQUEST_URI']);
        $endpoint = end($route);

        switch (strtolower($endpoint))
        {
            case 'access-denied':
                return TemplatesHandler::view("errors/access_denied.php",isDefaultView: true);
            case 'not-found':
                return TemplatesHandler::view("errors/not_found.php", isDefaultView: true);
            case 'forbidden':
                return TemplatesHandler::view("errors/forbidden.php", isDefaultView: true);
        }
        return TemplatesHandler::view("errors/server_error.php", isDefaultView: true);
    }
}