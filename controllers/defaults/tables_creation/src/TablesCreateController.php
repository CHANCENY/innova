<?php

namespace Innova\Controller\routers\tables_creation\src;

use Innova\Configs\ConfigHandler;
use Innova\Databases\Database;
use Innova\Databases\Tables;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class TablesCreateController
{
    public function TablesCreationPage(): mixed
    {
        /**
         * create all tables needed
         */
        global $middle;
        $request = new Request();
        $database = ConfigHandler::config("database");
        $site = ConfigHandler::config("site_information");

        $data['site'] = $site['name'];
        $data['logo'] = $site['logo'];
        $data['site_email'] = $site['email'];
        $data['database'] = $database['dbname'];
        $data['databaseUser'] = $database['dbUser'];
        $data['startOver'] = "/$middle/database/tables/initialization/2";
        $data['loading'] = $request->httpSchema(). "/controllers/defaults/tables_creation/assets/image/loading.gif";
        if($request->get("start_over") === 2)
        {
            if($this->removeInstallations())
            {
                $request->redirection("/innova/installation", true, 301);
            }
        }

        if($request->get("start_over") === 3)
        {
            if($this->initializeTable()){
                http_response_code(200);
                return ['status'=>200];
            }else{
                http_response_code(400);
                return ['status'=>404];
            }
        }
        return TemplatesHandler::view("tables_creation/tables_creation.php", $data, isDefaultView: true);
    }

    private function removeInstallations(): bool
    {
        if(Database::removeDatabase())
        {
            if(ConfigHandler::configRemove("database"))
            {
                if(ConfigHandler::configRemove("site_information"))
                {
                    return true;
                }
            }
        }
        return false;
    }

    private function initializeTable(): bool
    {
        return Tables::installTables();
    }
}