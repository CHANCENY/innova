<?php
namespace Innova\Controller\routers\types\src;

use Innova\Content\ContentType;
use Innova\Templates\TemplatesHandler;

class ContentTypes extends ContentType
{

  public function page(): mixed
  {
    return TemplatesHandler::view("types/content_listing.php", ['data'=>$this->contentListing()], TRUE);
  }

}