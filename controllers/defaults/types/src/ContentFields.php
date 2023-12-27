<?php
namespace Innova\Controller\routers\types\src;

use Innova\Content\ContentType;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class ContentFields
{

  public function page(): mixed {
    $req = new Request();
    $content = new ContentType();
    $definition = $content->contentType($req->get("name", ""))->getContentDefinitions();
    return TemplatesHandler::view("types/fields.php",['data'=>$definition], TRUE);
  }

}