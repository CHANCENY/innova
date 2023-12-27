<?php

namespace Innova\Controller\Custom\homepage\src;

use Innova\modules\CurrentUser;
use Innova\Templates\TemplatesHandler;

class HomePageController extends CurrentUser
{
    public function homePage(): mixed
    {
        $data['firstname'] = $this->firstname();
        return TemplatesHandler::view("homepage/homepage.php", $data);
    }
}