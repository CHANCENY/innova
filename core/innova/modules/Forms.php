<?php

namespace Innova\modules;

use Innova\Configs\ConfigHandler;
use Innova\Databases\Insert;
use Innova\Databases\Select;
use Innova\request\Request;
use Innova\Sessions\Session;

class Forms
{
    public function registrationUser(): bool|int
    {
        $schemas = ConfigHandler::config("system_storages");
        if(!empty($schemas))
        {
            $userSchema = [];
            foreach ($schemas as $key=>$schema)
            {
                if(!empty($schema['table']) && $schema['table'] === "users" &&
                !empty($schema['install'])  && $schema['install'] === true)
                {
                    $userSchema = $schema;
                }
            }

            if(!empty($userSchema))
            {
                $registerRequires = array_keys($userSchema['columns']);
                $request = new Request();
                foreach ($registerRequires as $key=>$field)
                {
                    $registerRequires[$field] = $request->post($field);
                }
                $data = array_filter($registerRequires, function ($key) {
                    return !is_numeric($key);
                }, ARRAY_FILTER_USE_KEY);

                $file = new Files();
                $file->uploadFromForm("image");
                $data['image'] = $file->finishUpload(1);

                try {
                    $data['birthday'] = (new \DateTime($data['birthday'] ?? "now"))->format("d-m-Y");
                }catch (\Throwable $e){

                }

                if($data['password'] !== $request->post("confirm"))
                {
                    return false;
                }

                if(!filter_var($data['mail'],FILTER_VALIDATE_EMAIL))
                {
                    return false;
                }
                unset($data['uid']);
                $data['roles'] = $data['roles'] === "admins" ? $data['roles'] . ",authenticated" : $data['roles'];
                $data['blocked'] = 0;
                foreach ($userSchema['columns'] as $key=>$value)
                {
                    if(in_array("not empty", $value) && empty($data[$key]))
                    {
                        return false;
                    }
                }
                $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
                $userExist = Select::find("users",['mail'=>$data['mail']]);
                if(empty($userExist))
                {
                    $userExist = Select::find("users",['username'=>$data['username']]);
                    if(empty($userExist))
                    {
                        return Insert::insert("users",$data);
                    }
                }
            }
        }
      return false;
    }

    public static function registerUser(): bool|int
    {
        return (new static())->registrationUser();
    }

    public  function siteIsNew(): bool
    {
        if(!empty(ConfigHandler::config("database")))
        {
            try{
                return empty(Select::records("users",['uid']));
            }catch (\Throwable $e)
            {
                return true;
            }
        }
        return true;
    }

    public function loginUser(string $username, string $password): bool
    {
        $password = htmlspecialchars(strip_tags($password));
        $username = htmlspecialchars(strip_tags($username));
        $attemptingUser = Select::find("users",["username"=>$username]);
        if(empty($attemptingUser))
        {
            $attemptingUser = Select::find("users", ['mail'=>$username]);
            if(empty($attemptingUser))
            {
                return false;
            }
        }
        $pass = $attemptingUser[0]['password'] ?? "";
        if(password_verify($password, $pass))
        {
            return Session::set("user", $attemptingUser,"private");
        }
        return false;
    }

    public static function login(string $user, string $pass): bool
    {
        return (new static())->loginUser($user, $pass);
    }

}