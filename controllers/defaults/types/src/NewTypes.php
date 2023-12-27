<?php
namespace Innova\Controller\routers\types\src;


use Innova\Content\ContentType;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class NewTypes
{

  public function page(): mixed {
    $this->saveContentType(new Request());
    return TemplatesHandler::view("types/new_content_creation.php", isDefaultView: TRUE);
  }

  private function saveContentType(Request $param) {
    if($param->method() === "post")
    {
      $machine_name = (new ContentType())
        ->create(
          $param->post("content_name"),
          $param->post("status"),
          $param->post("description"),
          $param->post("permission")
        );

      if(!empty($machine_name))
      {
        Messager::message()->addMessage("Content type added successfully");
        $param->redirection("/type/content/listing");
      }else{
        Messager::message()->addError("Content type creation failed");
        $param->redirection("/types/create/new");
      }
    }
  }

}