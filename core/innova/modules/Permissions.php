<?php

namespace Innova\modules;

use Innova\Configs\ConfigHandler;
use Innova\Databases\Query;

class Permissions {

    /**
     * @param string $permission
     * Permission label
     *
     * @return bool
     * True if save.
     */
    public function addNewRole(string $permission): bool {
        $allRoles = ConfigHandler::config('permissions_roles');
        $machineName = strtolower(trim(str_replace(' ','_', $permission)));
        $data = [
            'label'=>$permission,
            'name' =>$machineName
        ];
        if(empty($allRoles)) {
            return ConfigHandler::create('permissions_roles',[$data]);
        }
        else {
            foreach ($allRoles as $role) {
                if($machineName === $role['name']) {
                    return false;
                }
            }
            ConfigHandler::createBackUp('permissions_roles');
            ConfigHandler::configRemove('permissions_roles');
            $allRoles[] = $data;
            return ConfigHandler::create('permissions_roles', $allRoles);
        }
    }

    /**
     * @param string $permission
     * Permission machine_name.
     *
     * @return bool
     * True if removed.
     */
    public function removeRole(string $permission): bool {
        $allRoles = ConfigHandler::config('permissions_roles');
        $flag = false;
        if(!empty($allRoles)) {
            foreach ($allRoles as $key=>$role) {
                if($role['name'] === $permission) {
                    unset($allRoles[$key]);
                    $flag = true;
                    break;
                }
            }
        }

        if($flag) {
            ConfigHandler::createBackUp('permissions_roles');
            ConfigHandler::configRemove('permissions_roles');
            return ConfigHandler::create('permissions_roles', $allRoles);
        }
        return false;
    }

    /**
     * @return array
     * All roles.
     */
    public function roles(string $exclude_permission = null):array {
        $roles = ConfigHandler::config('permissions_roles') ?? [];
        if(!empty($roles)) {
            try {
                foreach ($roles as $k=>$role) {
                    if(!empty($role['name']) && $role['name'] === $exclude_permission) {
                        unset($roles[$k]);
                    }
                }
            }catch (\Throwable $e) {
                return [
                    ['label'=> 'Administrator', 'name'=>'admins'],
                    ['label' => 'Authenticated', 'name'=>'authenticated'],
                    ['label' => 'Anonymous', 'name'=> 'anonymous']
                ];
            }
        }else {
            ConfigHandler::create('permissions_roles',[
                ['label'=> 'Administrator', 'name'=>'admins'],
                ['label' => 'Authenticated', 'name'=>'authenticated'],
                ['label' => 'Anonymous', 'name'=> 'anonymous']
            ]);

            return [
                ['label'=> 'Administrator', 'name'=>'admins'],
                ['label' => 'Authenticated', 'name'=>'authenticated'],
                ['label' => 'Anonymous', 'name'=> 'anonymous']
            ];
        }
        return $roles;
    }


    public static function Permission(): Permissions {
        return (new Permissions());
    }

    public function AddAccess(string $permission, string $rule_module): bool
    {
        $allRoles = ConfigHandler::config('permissions_roles');
        if(empty($allRoles)) {
            $this->roles();
            $allRoles = ConfigHandler::config('permissions_roles');
        }

        foreach ($allRoles as $key=>$role) {
            if($role['name'] === $permission) {
                $allRoles[$key]['access'][] = $rule_module;
            }
        }
        ConfigHandler::createBackUp('permissions_roles');
        ConfigHandler::configRemove('permissions_roles');
        return ConfigHandler::create('permissions_roles',$allRoles);
    }

    public function removeAccess(string $role, string $access): bool
    {
        $allRoles = ConfigHandler::config('permissions_roles');
        if(empty($allRoles)) {
            return true;
        }

        foreach ($allRoles as $key=>$r) {
            if($r['name'] === $role && !empty($r['access'])) {
                $accessList = [];
                foreach ($r['access'] as $acc) {
                    if($acc !== $access) {
                       $accessList[] = $acc;
                    }
                }
                $allRoles[$key]['access'] = $accessList;
                break;
            }
        }
        ConfigHandler::createBackUp('permissions_roles');
        ConfigHandler::configRemove('permissions_roles');
        return ConfigHandler::create('permissions_roles', $allRoles);
    }

    public function findPermission(string $permission):array
    {
        $allRoles = ConfigHandler::config('permissions_roles');
        if(empty($allRoles)) {
            return [];
        }
        foreach ($allRoles as $role) {
            if($role['name'] === $permission) {
                return $role;
            }
        }
        return [];
    }

    public function AddActionsPermissions(mixed $role, mixed $access): bool
    {
        $allRoles = ConfigHandler::config('permissions_roles');
        if($allRoles) {
            foreach ($allRoles as $k=>$r) {
                if($r['name'] === $role) {
                    $allRoles[$k]['action_permissions'][] = $access;
                    break;
                }
            }

            ConfigHandler::createBackUp('permissions_roles');
            ConfigHandler::configRemove('permissions_roles');
            return ConfigHandler::create('permissions_roles',$allRoles);
        }
        return false;
    }

    public function deleteActionsPermissions(mixed $role, mixed $access): bool
    {
        $allRoles = ConfigHandler::config('permissions_roles');
        if(empty($allRoles)) {
            return true;
        }

        foreach ($allRoles as $key=>$r) {
            if($r['name'] === $role && !empty($r['action_permissions'])) {
                $accessList = [];
                foreach ($r['action_permissions'] as $acc) {
                    if($acc !== $access) {
                        $accessList[] = $acc;
                    }
                }
                $allRoles[$key]['action_permissions'] = $accessList;
                break;
            }
        }
        ConfigHandler::createBackUp('permissions_roles');
        ConfigHandler::configRemove('permissions_roles');
        return ConfigHandler::create('permissions_roles', $allRoles);
    }
}