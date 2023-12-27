<?php

namespace Innova\Controller\Custom\navigation\src;

use Innova\Templates\TemplatesHandler;

class Navigation
{
    public function navigationBuild(): string
    {
        return TemplatesHandler::view("navigation/navigation.php");
    }
}