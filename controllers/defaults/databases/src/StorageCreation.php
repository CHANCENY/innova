<?php
namespace Innova\Controller\routers\databases\src;

use Innova\modules\Messager;
use Innova\modules\StorageDefinition;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class StorageCreation
{
    public function page(): mixed {
        $this->storageCreation();
        $s = (new StorageDefinition())->storages()->getStorages();
        $data['storages'] = json_encode($s,JSON_PRETTY_PRINT);
        return TemplatesHandler::view("databases/storage_creations.php", $data,isDefaultView: true);
    }

    private function storageCreation()
    {
        $req = new Request();
        if($req->method() === 'post')
        {
            $data['table'] = strtolower(str_replace(' ', '_',$req->post('storage_name')));
            for ($i = 1; $i < 100; $i++)
            {
                if(!empty($req->post("field_$i")) &&
                    !empty($req->post("type_$i")) &&
                    !empty($req->post("option_$i")) &&
                    $req->post("option_$i") === "primary key" ||
                    $req->post("option_$i") === "unique"
                ){
                    $data['columns'][$req->post('field_'. $i)] =
                         [
                            $req->post("type_$i"),
                            $req->post("option_$i"),
                            "auto_increment"
                        ];
                    $data['indexes'][] = [
                        "{$req->post('field_'. $i)}" => "idx_{$req->post('field_'. $i)}"
                    ];
                }
                elseif (!empty($req->post("field_$i")) &&
                    !empty($req->post("type_$i")) &&
                    !empty($req->post("option_$i"))
                ){
                    $data['columns'][$req->post('field_'. $i)] =
                        [
                            $req->post("type_$i"),
                            $req->post("option_$i"),
                        ];
                }
            }

            $data['install'] = true;
            $str = new StorageDefinition();
            if ($str->addDefinition($data))
            {
                Messager::message()->addMessage("Added storage definition, if auto update not set please enabled it to persist definition to database");
            }else{
                Messager::message()->addError("Failed to save storage definition");
            }
        }
    }
}