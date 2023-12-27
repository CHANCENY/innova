<?php
namespace Innova\Controller\routers\users\src;


use Innova\Entities\User;
use Innova\modules\Messager;
use Innova\modules\Site;
use Innova\request\Request;
use Innova\Sessions\Session;
use Innova\Templates\TemplatesHandler;

class ResetPasswordTokenLink
{
    private array $view;
    public function page(): mixed {
        $req = new Request();
        $token = $req->get("token");
        if(empty($token))
        {
            Messager::message()->addWarning("Your reset token is missing");
            $req->redirection("/");
        }
        $sessionToken = Session::get("reset_code");
        if($sessionToken !== $token)
        {
            Messager::message()->addError("Token is invalid");
            $req->redirection("/");
        }
        $this->view['current_url'] = $req->httpSchema() . "/users/password/token/$token";
        $this->view['logo'] = Site::logo();
        $this->resetPasswordByToken();
        return TemplatesHandler::view("users/token_reset_password.php",$this->view ,isDefaultView: true);
    }

    private function resetPasswordByToken(): void
    {

        $req = new Request();
        if($req->method() !== 'post')
        {
            return;
        }
        $newPassword = $req->post("new_password");
        $confirmPassword = $req->post("confirm_password");

        if(!empty($newPassword) && !empty($confirmPassword))
        {
            if($newPassword === $confirmPassword)
            {
                $user = User::load(Session::get("reset_by"));
                if(!empty($user->id()))
                {
                    $user->setPassword($newPassword);
                    if($user->save())
                    {
                        Messager::message()->addMessage("Resetting password done successfully");
                        $req->redirection("/");
                    }
                }
            }
        }
        Messager::message()->addWarning("Resetting password encountered error");
    }
}