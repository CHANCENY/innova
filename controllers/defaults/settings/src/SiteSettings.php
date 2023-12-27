<?php
namespace Innova\Controller\routers\settings\src;

use Innova\Controller\routers\databases\src\DatabaseCreateController;
use Innova\Templates\TemplatesHandler;

class SiteSettings extends DatabaseCreateController
{
    public function page(): mixed {
        $this->databaseCreationForm();
        return TemplatesHandler::view("settings/site_settings.php", isDefaultView: true);
    }


}