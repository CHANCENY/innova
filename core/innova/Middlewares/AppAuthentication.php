<?php

namespace Innova\Middlewares;

use chillerlan\QRCode\QRCode;
use Innova\Databases\Delete;
use Innova\Databases\Insert;
use Innova\Databases\Select;
use Innova\Databases\Update;
use Innova\Entities\User;
use Innova\interfaces\AuthAuthenticationInterface;
use Innova\modules\CurrentUser;
use Innova\modules\Messager;
use Innova\modules\Site;
use OTPHP\TOTP;


//use QRcode;

class AppAuthentication implements AuthAuthenticationInterface {

  private string $path;

  public function settingWarning(): void {
    $user = new CurrentUser();
    $control = active_controller();
    $control->setTables("authentication_app");
    $control->setAllowed("select");
    if(!empty($user->id()))
    {
      $userOtp = Select::find("authentication_app",['uid'=>$user->id(),'verified'=>1]);
      if(empty($userOtp[0]['uid']))
      {
        $config = new Auth();
        $method = $config->getAuthenticationMethod();
        $transmission = $config->getTransmission();
        if($method === "2fa" && $transmission === "app")
        {
          Messager::message()->addInfo("Please Enable App Two Authentication. This site has App 2FA enabled for your security
          visit: <a href='@link'>Enable App 2FA</a>", ['@link'=>fullURI("/session/two/enabling/{$user->id()}")]);
        }
      }
    }
  }

  public function enablingUser(int $uid): bool {
    if(!empty($uid)) {
      $secret = TOTP::create()->getSecret();
      $enable = false;
      if(!empty($secret)) {
        $old = Select::find("authentication_app",['uid'=>$uid]);
        if(!empty($old)) {
          $enable = Update::update(
            "authentication_app",
            [
              'verified'=>2,
              "secret_keys"=>$secret
            ],
            [
              'uid'=>$uid
            ]
          );
        }
        else
        {
          $enable = Insert::insert(
            "authentication_app",
            [
              "uid"=>$uid,
              "secret_keys"=> $secret,
              "verified" => 2
            ]
          );
        }

        if(!empty($enable))
        {
          $totp = TOTP::create($secret);

          // Generate a URL for the QR code
          $user = User::load($uid);
          $totp->setIssuer(Site::name());
          $totp->setLabel($user->mail());
          $data = $totp->getProvisioningUri();
          $this->path = (new QRCode())->render($data);
          return !empty($this->path);
        }
      }
    }
    return false;
  }

  public function disablingUser(int $uid): bool {
    return Delete::delete("authentication_app",['uid'=>$uid]);
  }

  public function verificationUserCode(string $code, int $uid): bool {
    if(!empty($code) && !empty($uid)) {
      $secret = Select::find("authentication_app",['uid'=>$uid]);
      if(!empty($secret[0]['secret_keys'])){
        $secretKey = $secret[0]['secret_keys'];

        // Create a TOTP instance with the user's secret
        $totp = TOTP::create($secretKey);

        // Verify the TOTP entered by the user
        return $totp->verify($code);
      }
    }
    return false;
  }

  public function getPath() {
    return $this->path;
  }

}