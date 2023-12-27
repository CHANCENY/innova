<?php
namespace Innova\Controller\routers\users\src;

use Innova\modules\CurrentUser;
use Innova\modules\Messager;
use Innova\request\Request;

class LoginTokens
{

  public function page(): mixed {

    $request = new Request();
    $token = $request->get("token", null);

    if(empty($token)) {
      Messager::message()->addError("Token missing");
      $request->redirection("/errors/access-denied");
    }
    $currentUser = new CurrentUser();
    if($currentUser->verifyUserToken($token))
    {
      $currentUser->removeToken($token);
      $request->redirection("/");
    }
    return <<<FAILED
<div class="page-wrapper">
    <div class="content">
        <div class="row"><div class="col-md-12"><h1 class="m-auto">Authentication Failed.</h1></div></div>
    </div>
</div>
FAILED;
  }

}