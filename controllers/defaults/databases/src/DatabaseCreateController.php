<?php

namespace Innova\Controller\routers\databases\src;

use Innova\Configs\ConfigHandler;
use Innova\Databases\Database;
use Innova\modules\Files;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class DatabaseCreateController
{
    public function databaseCreationForm(): mixed
    {
        $message = null;
        $request = new Request();
        if($request->post("create_site"))
        {
            $data['name'] = $request->post("site_name");
            $data['slogan'] = $request->post("site_slogan");
            $data['email'] = $request->post("site_email");

            $file = new Files();
            $file->uploadFromForm("site_logo");
            $logo = $file->finishUpload(1);
            $data['logo'] = $logo;
            if(ConfigHandler::create("site_information", $data))
            {
                $message = <<<ALERT
<div class="alert alert-success alert-dismissible fade show" role="alert">
								<strong>Success!</strong> Site <a href="#" class="alert-link">Information</a> has been saved successfully.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
ALERT;
            }else{
                $message = <<<ALERT
<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Error!</strong> A <a href="#" class="alert-link">problem</a> has been occurred while submitting your data.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
ALERT;
            }
        }

        if($request->post("create_db"))
        {
            $message = $this->creatingDatabase($request);
            if($message === true)
            {
                global $middle;
                $request->redirection("/database/tables/initialization/1", true, 301);
            }
        }
        $uri = $request->httpSchema() ."/database/create";
        return TemplatesHandler::view("databases/create_database_form.php",['url'=>$uri, 'msg'=>$message], isDefaultView: true);
    }

    private function creatingDatabase(Request $request): string|bool
    {
        $dbConfiguration['dbname'] = $request->post("database_name");
        $dbConfiguration['host'] = $request->post("database_host");
        $dbConfiguration['dbPassword'] = $request->post("database_password");
        $dbConfiguration['dbUser'] = $request->post("database_user");
        $dbConfiguration['dbType'] = $request->post("database_type");

        $keys = array_keys($dbConfiguration);
        foreach ($keys as $a=>$key)
        {
            if($key !== "dbPassword")
            {
                if(empty($dbConfiguration[$key]))
                {
                     return <<<ALERT
<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Error!</strong> A <a href="#" class="alert-link">problem</a> has been occurred while submitting your data.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
ALERT;
                }
            }
        }

        $massage = null;
        $saved = ConfigHandler::create("database",$dbConfiguration);
        if($saved)
        {
            $massage .= <<<ALERT
<div class="alert alert-success alert-dismissible fade show" role="alert">
								<strong>Success!</strong> Database <a href="#" class="alert-link">Information</a> has been saved successfully.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
ALERT;
            if(Database::create())
            {
                $massage = true;
            }else{
               $massage .= <<<ALERT
<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Error!</strong> A <a href="#" class="alert-link">problem</a> has been occurred while creating database.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
ALERT;
            }
        }else{
            $massage .= <<<ALERT
<div class="alert alert-danger alert-dismissible fade show" role="alert">
								<strong>Error!</strong> A <a href="#" class="alert-link">problem</a> has been occurred while saving your database information.
								<button type="button" class="close" data-dismiss="alert" aria-label="Close">
									<span aria-hidden="true">&times;</span>
								</button>
							</div>
ALERT;
        }
        return $massage;
    }
}