<?php

namespace Innova\Controller\routers\dashboard\src;

use Innova\Abstracted\Dashboard;
use Innova\modules\CurrentUser;
use Innova\Templates\TemplatesHandler;

class DashboardController extends Dashboard
{
    public function innovaDashboard(): mixed
    {
        $data['firstname'] = (new CurrentUser())->firstname();
        $data['dashboard'] = $this;
        return TemplatesHandler::view("dashboard/dashboard.php", $data, true);
    }

}