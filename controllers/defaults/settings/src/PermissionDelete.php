<?php
namespace Innova\Controller\routers\settings\src;

use Innova\modules\Messager;
use Innova\modules\Permissions;
use Innova\request\Request;

class PermissionDelete
{

    public function page(): mixed {
        $request = new Request();
        $role = $request->get('role');

        if(!empty($role)) {
            if(Permissions::Permission()->removeRole($role)) {
                Messager::message()->addMessage("Deleted");
            }else {
                Messager::message()->addError("Error: failed to delete role");
            }
        }
        $request->redirection('settings/permission');
    }

}