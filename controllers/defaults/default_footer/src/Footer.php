<?php

namespace Innova\Controller\routers\default_footer\src;

use Innova\modules\Messager;
use Innova\Templates\TemplatesHandler;

class Footer
{
    public function footerBuild(): mixed
    {
        $data['copyright'] = "All Right reserved &copy; ". (new \DateTime("now"))->format("Y");
        $data['msg'] = Messager::message()->showMessages();
        return TemplatesHandler::view("footer/footer.php", $data, isDefaultView: true);
    }
}