<?php
namespace Innova\Controller\routers\users\src;


use Innova\modules\CurrentUser;
use Innova\modules\Messager;
use Innova\modules\Site;
use Innova\request\Request;
use Innova\Sessions\Session;

class UserOut
{
    public function page(): mixed {
        if(!empty((new CurrentUser())->id()))
        {
            Session::clear("user", "private");
            $name = (new Request())->get("firstname");
            Messager::message()->addMessage("You have logged out $name from @site", ['@site'=>Site::name()]);
            (new Request())->redirection("/");
        }

        return "<h1>Signing out failed</h1>";
    }


}