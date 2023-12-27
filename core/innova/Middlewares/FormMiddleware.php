<?php

namespace Innova\Middlewares;

use Innova\Configs\ConfigHandler;
use Innova\Entities\Client;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Sessions\Session;
use Random\RandomException;

class FormMiddleware
{
  private Client $client;

  /**
   * Construct.
   */
  public function __construct()
  {
    $this->client = new Client();
  }

  /**
   * Generate token for csrf.
   * @return string|null
   * @throws RandomException
   */
  public function csrf(): string|null {
    $token = password_hash(base64_encode(random_bytes(50)), PASSWORD_BCRYPT);
    $controller = active_controller();
    $currentURL = $controller->getRouteUri();
    $ipAddress = $this->client->getIPAddress();
    $userAgent = $this->client->getUserAgent();
    if(in_array("POST", $controller->getHeaders()['method']))
    {
      $old = [
        'userAgent' => $userAgent,
        'ipAddress' => $ipAddress,
        'currentURL' => $currentURL,
        'time' => time(),
        'token' => $token
      ];
      $token = str_replace([',','/','$'],['-','-','-'], $token);
      Session::set("csrf_token,$token", $old);
      return $token;
    }
    return null;
  }

  /**
   * For every form will definitely send post request this request
   * will be intercepted and checked for csrf token.
   * @return void
   */
  public function csrfValidation(): void {

    $forms = ConfigHandler::config("forms_settings");
    $request = new Request();
    if($request->method() === "post"  && !empty($forms) && $forms['enabled'] === "on") {
      $controller = active_controller();
      $currentURL = $controller->getRouteUri();
      $ipAddress = $this->client->getIPAddress();
      $userAgent = $this->client->getUserAgent();

      $csrf = ConfigHandler::config("csrf_tokens");
      if(!empty($csrf)) {
        $thisFormToken = $request->post("csrf", null);
        $found = Session::get("csrf_token,$thisFormToken");
        if(!empty($found))
        {
          Session::clear("csrf_token,$thisFormToken");
          $time = $found['time'];

          // Calculate the time difference in seconds
          $timeDifference = time() - $time;

          if ($timeDifference >= 30 * 60) {
            Messager::message()->addError("Form has expired. Error form not submitted.");
            $request->redirection("/");
          }

          if($found['currentURL'] !== $currentURL)
          {
            Messager::message()->addError("Form Origin Changed. Error form not submitted.");
            $request->redirection("/");
          }

          if($found['ipAddress'] !== $ipAddress) {
            Messager::message()->addError("Form Origin undetermined. Error form not submitted.");
            $request->redirection("/");
          }

          if($found['userAgent'] !== $userAgent) {
            Messager::message()->addError("Form submission from invalid client. Error form not submitted.");
            $request->redirection("/");
          }
        }else{
          Messager::message()->addError(
            "This form is not allowed to submit data on this site. please report to administrator
                         if this flag is wrong."
          );
          $request->redirection("/");
        }
      }else{
        Messager::message()->addError("This form origin is unknown");
        $request->redirection("/");
      }
    }
  }
}