<?php

namespace Innova\Controller\routers\users\src;

use http\Exception\BadUrlException;
use Innova\Databases\Query;
use Innova\Databases\Select;
use Innova\modules\CurrentUser;
use Innova\modules\Forms;
use Innova\modules\Messager;
use Innova\modules\Permissions;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class RegistrationController
{
    public function registrationUser(): mixed
    {
        $request = new Request();
        $data['uri'] = $request->httpSchema(). "/users/register";

        $all = Permissions::Permission()->roles();
        $options = "";
        $currentUser = new CurrentUser();
        try {
            $user = Query::query("SELECT * FROM users WHERE roles LIKE '%admins%'")->queryRunner()->getResult();
            foreach ($all as $key=>$value) {

                if($value['name'] === 'admins' && ($currentUser->isAdmin() ||empty($user))) {
                    $options .= "<option value='{$value['name']}'>{$value['label']}</option>";
                }elseif ($value['name'] !== 'admins') {
                    $options .= "<option value='{$value['name']}'>{$value['label']}</option>";
                }
            }
        }catch (\Throwable $e) {

        }
        $data['roles'] = $options;
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