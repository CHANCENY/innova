<?php
namespace Innova\Controller\routers\files\src;


use Innova\Entities\FileSystem;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class FilesManagement
{
    public function page(): mixed {

        $req = new Request();

        $page = $req->get('page', 0);
        $search = $req->get("search", null);
        $filesObject = FileSystem::load();
        $filesObject->searchFile($search);
        $files = $filesObject->getFiles() ?? [];

        $chunked = array_chunk($files, 20);

        if(intval($page) === 0) {
            $chunked = $chunked[0] ?? [];
        }
        elseif(count($chunked) >= intval($page) + 1){
            $chunked = $chunked[$page];
        }else{
            $req->redirection("/files/content");
        }
        $keys = array_keys($chunked ?? []);
        $url = $req->httpSchema() . "/files/content";
        return TemplatesHandler::view("files/listing_files.php",
            [
                'data'=>$chunked,
                'keys'=>$keys
            ],
            isDefaultView: true
        );
    }
}