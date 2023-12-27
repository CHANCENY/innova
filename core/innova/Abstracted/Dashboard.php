<?php

namespace Innova\Abstracted;

use Innova\Configs\ConfigHandler;
use Innova\Databases\Query;
use Innova\Databases\Select;
use Innova\interfaces\DashboardInterface;
use Innova\Middlewares\Auth;
use Innova\modules\StorageDefinition;
use Innova\Routes\Routes;

abstract class Dashboard implements DashboardInterface {

    public function administrators(): array
    {
        try {
            return Query::query("SELECT uid AS id, image, firstname, lastname FROM users WHERE roles LIKE '%admins%'")->getResult();
        }catch (\Throwable $e){
           return [];
        }
    }

    public function usersCount(): int
    {
        try {
            $query = Query::query("SELECT COUNT(uid) as total FROM users");
            return $query->getResult()[0]['total'] ?? 0;
        }catch (\Throwable $exception){
            return 0;
        }
    }

    public function routeCount(): int
    {
        return count((new Routes())->getRoutesCollection());
    }

    public function storageCount(): int
    {
        return count((new StorageDefinition())->storages()->getStorages());
    }

    public function fileCount(): int
    {
        try {
            $query = Query::query("SELECT COUNT(fid) as total FROM file_managed");
            return $query->getResult()[0]['total'] ?? 0;
        }catch (\Throwable $exception){
            return 0;
        }
    }

    public function authentication(): string
    {
        $auth = new Auth();
        return $auth->getAuthenticationMethod();
    }

    public function csrfSecurity(): bool
    {
        $form = ConfigHandler::config("forms_settings");
        return !empty($form['enabled']);
    }

    public function mailSetting(): bool
    {
        $email = ConfigHandler::config("email_settings");
        foreach ($email as $value) {
            if(isset($value['active']) && $value['active'] === true){
                return true;
            }
        }
        return false;
    }

    public function errorSetting(): bool
    {
        $error = ConfigHandler::config("error_setting");
        return !empty($error['enabled']);
    }

    public function errorCount(): int
    {
        return count(array_diff(scandir(
            "sites/settings/application/errors"),
        ['.', '..', '...']
        ));
    }

    public function modulesCount(): int
    {
        return count(array_diff(scandir(
            "modules/custom"),
            ['.', '..']
        ));
    }
}