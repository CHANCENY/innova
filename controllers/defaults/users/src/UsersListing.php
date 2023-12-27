<?php
namespace Innova\Controller\routers\users\src;

use Innova\Entities\User;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class UsersListing extends Request
{
    private array $view;

    public function page(): mixed 
    {
        $this->view['host'] = $this->httpSchema();
        $this->view['data'] = User::users();
        return TemplatesHandler::view("users/users_listing.php",$this->view, true);
    }
}