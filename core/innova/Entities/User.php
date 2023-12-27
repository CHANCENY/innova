<?php

namespace Innova\Entities;

use Innova\Databases\Delete;
use Innova\Databases\Select;
use Innova\Databases\Update;
use Innova\modules\CurrentUser;
use Innova\request\Request;
use Innova\Sessions\Session;

class User
{
  private array $user;

  public function __construct(private readonly int $userID)
  {
    $this->user = Select::find("users",['uid'=>$this->userID])[0] ?? [];
  }

  public function roles(): string
  {
    return $this->user['roles'] ?? "";
  }

  /**
   * @return bool
   */
  public function isAdmin(): bool
  {
    $roles = (new CurrentUser())->roles();
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

  public function birthday(): string
  {
    return $this->user['birthday'] ?? "";
  }

  /**
   * @return int
   */
  public function id(): int
  {
    return $this->user['uid'] ?? 0;
  }

  public static function load(int $uid): User
  {
    return new User($uid);
  }

  public function setPassword(string $newPassword): User
  {
    $this->user['password'] = password_hash($newPassword, PASSWORD_BCRYPT);
    return $this;
  }

  public function setUsername(string $username): User
  {
    $this->user['username'] = $username;
    return $this;
  }

  public function setEmail(string $email): User
  {
    $this->user['mail'] = $email;
    return $this;
  }

  public function setPhone(string $phone): User
  {
    $this->user['phone'] = $phone;
    return $this;
  }

  public function setImage(string $imageUrl): User
  {
    $this->user['image'] = $imageUrl;
    return $this;
  }

  public function setOverView(string $overview): User
  {
    $this->user['overview'] = $overview;
    return $this;
  }

  public function setFirstname(string $firstname): User
  {
    $this->user['firstname'] = $firstname;
    return $this;
  }

  public function setLastname(string $lastname): User
  {
    $this->user['lastname'] = $lastname;
    return $this;
  }

  public function setVerified(bool $verified): User
  {
    $this->user['verified'] = $verified;
    return $this;
  }

  public function setBlocked(bool $blocked): User
  {
    $this->user['blocked'] = $blocked;
    return $this;
  }

  public function addRole(string $role): User
  {
    $this->user['roles'] .= ",$role";
    return $this;
  }

  public function removeRoleAdmin(): User
  {
    $list = explode(",", $this->user['roles']);
    $index = array_search('admins', $list);
    if(!empty($index))
    {
      unset($list[$index]);
    }
    $this->user['roles'] = implode(',', $list);
    return $this;
  }

  public function removeRoleAuthenticated(): User
  {
    $list = explode(",", $this->user['roles']);
    $index = array_search('authenticated', $list);
    if(!empty($index))
    {
      unset($list[$index]);
    }
    $this->user['roles'] = implode(',', $list);
    return $this;
  }

  public function save(): bool
  {
    $by = ['uid'=>$this->id()];
    unset($this->user['uid']);
    return Update::update("users",$this->user,$by);
  }

  public function set(string $key, $value): User
  {
    $this->user[$key] = $value;
    return $this;
  }

  public function delete(): bool
  {
    return Delete::delete('users', ['uid'=>$this->id()]);
  }

  public static function users(): array
  {
    $data = [
      'uid' => 'id',
      'firstname' => 'name',
      'mail' => 'email',
      'image' => 'img',
      'phone' => 'mobile',
      'roles' => 'role'
    ];
    return Select::records("users", $data);
  }

  public function oneLoginLink(): string {
    $time = time();
    $token = base64_encode(random_bytes( 100). "-" . $time);
    $token = str_replace([',','/'], ['-','-'],$token);
    Session::set("one_time,$token", [
        $this->mail(),
        $this->userID,
        $this->username(),
        $time,
      ]
    );
    $request = new Request();
    return $request->httpSchema() ."/session/token/$token";
  }

  public function otp(): array
  {
    $time = time();
    $token = random_int(10, 99) . random_int(10, 99) . random_int(10, 99);
    $key = base64_encode(random_bytes( 100));
    $key = str_replace([',','/'], ['-','-'],$key);
    Session::set("otp_time,$key", [
      "otp" => $token,
      "time" => $time,
      "uid" => $this->id()
    ]);
    return ['otp'=>$token, 'key'=>$key];
  }

}