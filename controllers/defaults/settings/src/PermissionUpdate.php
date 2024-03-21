<?php
namespace Innova\Controller\routers\settings\src;

use Innova\modules\Permissions;
use Innova\request\Request;

class PermissionUpdate
{

    public function page(): mixed {
        $request = new Request();
        $access = $request->post('access');
        $role = $request->post('id');
        $action = $request->post('action');

        if($action === 'add' && Permissions::Permission()->AddAccess($role, $access)) {
            http_response_code(200);
            return ['status' => 301,'message'=>'Access added'];
        }

        if($action === 'delete' && Permissions::Permission()->removeAccess($role, $access)) {
            http_response_code(200);
            return ['status' => 301,'message'=>'Access removed'];
        }

        if($action === 'get') {
            http_response_code(200);
            return Permissions::Permission()->findPermission($role);
        }

        if($action === 'actions-add') {
            if(Permissions::Permission()->AddActionsPermissions($role, $access)) {
                http_response_code(200);
                return ['status'=>200, 'message'=>'Saved'];
            }else {
                http_response_code(404);
                return ['status'=>200, 'message'=>'failed'];
            }
        }

        if($action === 'actions-delete') {
            $result = Permissions::Permission()->deleteActionsPermissions($role, $access);
            if($result) {
                http_response_code(200);
                return ['status'=>200, 'message'=>'removed'];
            }else {
                http_response_code(404);
                return ['status'=>200, 'message'=>'failed'];
            }
        }

        else {
           http_response_code(404);
           return ['status'=>404, 'message'=>'Updating proccess failed'];
        }
    }

}