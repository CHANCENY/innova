<?php

namespace Innova\Controller\routers\users\src;

use http\Exception\BadUrlException;
use Innova\modules\CurrentUser;
use Innova\modules\Forms;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class RegistrationController
{
    public function registrationUser(): mixed
    {
        $request = new Request();
        $data['uri'] = $request->httpSchema(). "/users/register";

        if($request->method() === "post")
        {
            if(!empty($request->post("user_create")))
            {
                $result = $this->createUser();
                if(!empty($result))
                {
                    Messager::message()->addMessage("User added successfully");
                    $request->redirection("/");
                }
                Messager::message()->addWarning("We have encountered error during user creation");
            }
        }
        return TemplatesHandler::view("registration/registration.php",$data, isDefaultView: true);
    }

    private function createUser(): bool|int
    {
        return Forms::registerUser();
    }
}