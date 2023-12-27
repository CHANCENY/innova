<?php

namespace Innova\Routes;

use Innova\modules\CurrentUser;
use Innova\request\Request;

class SecureController
{
    public function __construct(private readonly array $controller)
    {
        $this->checkControllerAccess();
    }

    private function checkControllerAccess(): void
    {
        $access = $this->controller['access'];
        $currentUser = new CurrentUser();

        //check roles
        $flagAccess = false;
        $allRoles = $currentUser->roles();
        $allRoles[] = "anonymous";

        foreach ($access as $key=>$value)
        {

            if(in_array(strtolower($value), $allRoles))
            {
                $flagAccess = true;
                break;
            }
        }

        if(!$flagAccess)
        {
            (new Request())->redirection("errors/access-denied",true, 302);
        }
    }

    public static function dashBoardAccess(): bool
    {
        $currentUser = new CurrentUser();
        $roles = $currentUser->roles();
        return in_array("admins", $roles);
    }
}