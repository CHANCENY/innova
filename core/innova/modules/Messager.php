<?php

namespace Innova\modules;

class Messager
{
  public function addMessage($message,array $options = []): void
  {
    global $messagesStorages;
    if(!empty($options))
    {
      $keys = array_keys($options);
      $values = array_values($options);
      $message  = str_replace($keys, $values, $message);
    }
    $messagesStorages[] = <<<MESSAGE
<div class="alert alert-success alert-dismissible fade show" role="alert">
								 $message
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
MESSAGE;
  }

  public function addError($message,array $options =[]): void
  {
    global $messagesStorages;
    if(!empty($options))
    {
      $keys = array_keys($options);
      $values = array_values($options);
      $message  = str_replace($keys, $values, $message);
    }
    $messagesStorages[] = <<<MESSAGE
<div class="alert alert-danger alert-dismissible fade show" role="alert">
								$message
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
MESSAGE;
  }

  public function addWarning($message,array $options = []): void
  {
    global $messagesStorages;
    if(!empty($options))
    {
      $keys = array_keys($options);
      $values = array_values($options);
      $message  = str_replace($keys, $values, $message);
    }
    $messagesStorages[] = <<<MESSAGE
<div class="alert alert-warning alert-dismissible fade show" role="alert">
								 $message
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
MESSAGE;
  }

  public function addInfo($message,array $options = []): void
  {
    global $messagesStorages;
    if(!empty($options))
    {
      $keys = array_keys($options);
      $values = array_values($options);
      $message  = str_replace($keys, $values, $message);
    }

    $messagesStorages[] = <<<MESSAGE
<div class="alert alert-info alert-dismissible fade show" role="alert">
								$message
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
MESSAGE;
  }

  public function showMessages(): string|null
  {
    global $messagesStorages;
    $messages = null;
    if(empty($messagesStorages))
    {
      return "";
    }
    if(gettype($messagesStorages) === "array")
    {
      $messages = implode('', $messagesStorages);
    }else{
      $messages = $messagesStorages;
    }
    unset($_SESSION['messages_all']);
    return $messages;
  }
  public static function message(): Messager
  {
    return new Messager();
  }

}