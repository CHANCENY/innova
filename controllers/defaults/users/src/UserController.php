<?php

namespace Innova\Controller\routers\users\src;

use Innova\Entities\User;
use Innova\Exceptions\AccessDeniedException;
use Innova\modules\CurrentUser;
use Innova\modules\Files;
use Innova\modules\Messager;
use Innova\modules\Permissions;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class UserController extends Request
{
    public function userInformation(): mixed
    {
        $uid = $this->get('user_id', null);
        $currentUser = new CurrentUser();
        if(!$currentUser->isAdmin() && $uid != $currentUser->id()) {
            $this->redirection('/errors/access-denied');
        }
        else
        {
            if(empty($this->get("user_id")))
            {
                $this->redirection("/");
            }
            $user = User::load($this->get("user_id"));
            $data['user'] = $user;
            $data['host'] = $this->httpSchema();
            $all = Permissions::Permission()->roles();
            $options = "";
            foreach ($all as $key=>$value) {

                $list = explode(',', $user->roles());
                if(in_array($value['name'], $list)) {
                    $options .= "<option value='{$value['name']}' selected>{$value['label']}</option>";
                }else {
                    $options .= "<option value='{$value['name']}'>{$value['label']}</option>";
                }
            }
            $data['roles'] = $options;
            $this->updateUserProfile();
            return TemplatesHandler::view("users/user_profile.php", $data, true);
        }
    }

    private function updateUserProfile()
    {
        $req = new Request();
        if($req->method() === 'post')
        {
            $data = [
                'firstname' =>null,
                'lastname' => null,
                'username' => null,
                'mail' => null,
                'phone' => null,
                'overview' => null,
                'address' => null,
                'verified' => null,
                'blocked' => null,
            ];
            foreach ($data as $key=>$value)
            {
                $data[$key] = $req->post($key);
            }
            $data['blocked'] = empty($data['blocked']) ? 0 : 1;
            $data['roles'] = implode(',', $_POST['roles']);
            $file = new Files();
            if(!empty($_FILES['image']['name']))
            {
                $file->uploadFromForm('image');
                $file = $file->finishUpload();
                if(!empty($file))
                {
                    $data['image'] =  $file;
                }
            }
            $user = User::load($req->get('user_id'));
            foreach ($data as $key=>$value)
            {
                if(!empty($user->id()))
                {
                    $user->set($key,$value);
                }
            }
            if($user->save())
            {
                $currentUser = new CurrentUser();
                if($currentUser->id() === $user->id())
                {
                    $currentUser->setCurrentUser($user->id());
                }
                Messager::message()->addMessage('User updated successfully');
            }else{
                Messager::message()->addError('User update failed');
            }
        }
    }
}