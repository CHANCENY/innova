<?php
namespace Innova\Controller\routers\databases\src;


use Innova\modules\StorageDefinition;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class StoragesListing extends StorageDefinition
{
    public function page(): mixed {
        $req = new Request();
        $name = $req->get("storage_name", null);
        $data['storages'] = $this->storage($name)->getStorages();
       return TemplatesHandler::view("databases/storages_listing.php",$data, isDefaultView: true);
    }
}