<?php
namespace Innova\Controller\routers\databases\src;


use Innova\modules\Messager;
use Innova\modules\StorageDefinition;
use Innova\request\Request;
use Innova\Sessions\Session;
use Innova\Templates\TemplatesHandler;

class StorageDeletion extends StorageDefinition
{
    public function page(): mixed {
        $req = new Request();
        if(!empty(Session::get("delete")))
        {
            $this->deleteStorage($req);
            Session::clear("delete");
            $req->redirection("/");
        }
        $name = $req->get("name", null);
        $data['storages'] = $this->storage($name)->getStorages();
        Session::set("delete", true);
        return TemplatesHandler::view("databases/storage_deletion.php",$data ,isDefaultView: true);
    }

    private function deleteStorage(Request $req)
    {
        if((new StorageDefinition())->deleteDefinition($req->get('name')))
        {
            Messager::message()->addMessage(
                "Successfully remove storage definition note that the table is still in database"
            );
            return;
        }
        Messager::message()->addError("Failed to remove storage definition");
    }
}