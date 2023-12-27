<?php

namespace Innova\modules;

use Innova\Databases\Select;
use Innova\Sessions\Session;

/**
 *
 */
class CurrentUser
{
    /**
     * @var array|mixed
     */
    private mixed $user;

    /**
     *
     */
    public function __construct()
    {
        $this->user = Session::get("user","private")[0] ?? [];
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->user['uid'] ?? 0;
    }

    /**
     * @return array
     */
    public function roles(): array
    {
        if(!empty($this->user['roles']))
        {
            return explode(",", $this->user['roles']);
        }
        return [];
    }

    /**
     * @param int $userID
     * @return bool
     */
    public function setCurrentUser(int $userID): bool
    {
        if(!empty($userID))
        {
            $user = Select::find("users",['uid'=>$userID]);
            if(!empty($user))
            {
                return Session::set("user", $user, "private");
            }
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        $roles = $this->roles();
        return in_array('admins', $roles);
    }

    /**
     * @return string
     */
    public function firstname(): string
    {
        return $this->user['firstname'] ?? "";
    }

    /**
     * @return string
     */
    public function lastname(): string
    {
        return $this->user['lastname'] ?? "";
    }

    /**
     * @return string
     */
    public function mail(): string
    {
        return $this->user['mail'] ?? "";
    }

    /**
     * @return string
     */
    public function password(): string
    {
        return $this->user['password'] ?? "";
    }

    /**
     * @return string
     */
    public function username(): string
    {
        return $this->user['username'] ?? "";
    }

    /**
     * @return string
     */
    public function phone(): string
    {
        return $this->user['phone'] ?? "";
    }

    /**
     * @return string
     */
    public function overview(): string
    {
        return $this->user['overview'] ?? "";
    }

    /**
     * @return string
     */
    public function verified(): string
    {
        return $this->user['verified'] ?? "";
    }

    /**
     * @return string
     */
    public function address(): string
    {
        return $this->user['address'] ?? "";
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        return !empty($this->user['blocked']);
    }

    /**
     * @return string
     */
    public function image(): string
    {
        return $this->user['image'] ?? "assets/defaults/img/user.jpg";
    }

    public function isLogged(): bool
    {
        return empty($this->id());
    }

    public function verifyUserToken(string $token):  bool
    {
      $tokenUser = Session::get("one_time,$token");
      if(!empty($tokenUser)) {
        $time = $tokenUser[3];
        $uid = $tokenUser[1];
        $email = $tokenUser[0];
        $username = $tokenUser[2];

        if(is_numeric($time) && is_numeric($uid) && gettype($email) === "string" && gettype($username) === "string")
        {
          $currentTime = time();
          if(($currentTime - $time) < 300){
            return $this->setCurrentUser($uid);
          }
        }
      }
      return false;
    }

    public function removeToken(string $token): void
    {
      Session::clear("one_time,$token");
    }
}