<?php
namespace Innova\Controller\routers\users\src;


use Innova\Databases\Select;
use Innova\Entities\User;
use Innova\MailManger\Mail;
use Innova\MailManger\Reciever;
use Innova\modules\CurrentUser;
use Innova\modules\Messager;
use Innova\modules\Site;
use Innova\request\Request;
use Innova\Sessions\Session;
use Innova\Templates\TemplatesHandler;
use Ramsey\Uuid\Uuid;

class ResetPassword extends CurrentUser
{
    private array $view;

    public function page(): mixed {

        $this->view['controller'] = $this;
        $this->view['currentUser'] = !$this->isLogged();
        $this->view['current_url'] = (new Request())->httpSchema() . "/users/password";
        $this->changePasswordAsLoggedUser();
        $this->sendingResetTokenConfirmation();
       return TemplatesHandler::view("users/reset_password.php",$this->view, isDefaultView: true);
    }

    private function changePasswordAsLoggedUser()
    {
        $request = new Request();
        if($request->method() === "post" && !empty($request->post("old_password")))
        {
            $newPassword = $request->post("new_password");
            $confirmPassword = $request->post("confirm_password");
            if($newPassword === $confirmPassword &&
                password_verify($request->post('old_password'), $this->password()))
            {
                $currentUser = new CurrentUser();
                $userID = $currentUser->id();
                if(!empty($userID))
                {
                    $user = User::load($userID);
                    $user->setPassword($newPassword);
                    if($user->save())
                    {
                        Messager::message()->addMessage("Password reset done successfully");
                    }else{
                        Messager::message()->addError("Failed to reset password");
                    }
                }
            }
        }
    }

    private function sendingResetTokenConfirmation()
    {
        $request = new Request();
        if ($request->method() === 'post' && !empty($request->post("email")))
        {
            if(filter_var($request->post("email"),FILTER_VALIDATE_EMAIL))
            {
                $user = Select::find("users",['mail'=>
                $request->post("email")]);
                if(!empty($user[0]['uid']))
                {
                    $user = User::load($user[0]['uid']);
                    $receiver = new Reciever([$user->mail()],[$user->firstname()]);
                    Session::set("reset_code", Uuid::uuid4()->toString());
                    Session::set("reset_by", $user->id());
                    $params = [
                        "subject" => "Reset Password",
                        "message"=>$this->body(Session::get("reset_code"))
                    ];
                    Mail::send($receiver,$params);
                }
            }
            Messager::message()->addInfo("We have send email with further instruction");
        }
    }

    private function body($code): string
    {
        $logo = Site::logo();
        $name = Site::name();
        $host = (new Request())->httpSchema();
        return <<<EMAIL
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }
        .logo {
            max-width: 150px;
        }
        .reset-code {
            font-size: 24px;
            margin-top: 20px;
        }
        .message {
            font-size: 16px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <img src="$logo" alt="$name" class="logo">
        <h2>Password Reset Link</h2>
        <p class="reset-code">Your password reset link is: <strong><a href="$host/users/password/token/$code">continue</a></strong></p>
        <p class="message">To reset your password, please continue with the link to $name reset your password.</p>
    </div>
</body>
</html>

EMAIL;
    }
}