<?php
namespace Innova\Controller\routers\settings\src;

use Innova\modules\Messager;
use Innova\modules\Permissions;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class PermissionCreation
{

    public function page(): mixed {

        $this->createRole(new Request());
       return TemplatesHandler::view('settings/permission_creation.php', isDefaultView: true);
    }

    private function createRole(Request $request): void
    {
        if($request->method() === 'post') {
            $role = $request->post('role');
            if(Permissions::Permission()->addNewRole($role)) {
                Messager::message()->addMessage("Created role $role successfully");
            }else {
                Messager::message()->addError("Error: creation of role failed");
            }
            $request->redirection('settings/permission');
        }
    }

}