<?php 
namespace Innova\Controller\routers\settings\src; 

use Innova\modules\Permissions;
use Innova\Templates\TemplatesHandler;

class Permission
{ 

public function page(): mixed {
    $data['permissions'] = Permissions::Permission()->roles();
    return TemplatesHandler::view('settings/permission.php', $data,isDefaultView: true);
}

}