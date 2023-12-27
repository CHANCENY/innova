<?php
namespace Innova\Controller\routers\files\src;


use Innova\modules\Files;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class FilesUpload
{
    public function page(): mixed {
        $this->uploadFiles();
        return TemplatesHandler::view("files/file_upload.php", isDefaultView: true);
    }

    private function uploadFiles()
    {
        $req = new Request();
        if($req->method() === 'post') {
            $file = new Files();
            $files = $file->uploadFromForm("files");
            $ff = $file->finishUpload(3);
            if(!empty($ff))
            {
                Messager::message()->addMessage("Successfully save  uploaded files");
            }else{
                Messager::message()->addError("Failed to save uploaded files");
            }

            $files = new Files();
            if(!empty((new Request())->post("url")))
            {
              $re = new Request();
              $url = $req->post("url");
              $files->uploadFromUrl($url);
              $ff = $files->finishUpload(3);
              if(!empty($ff))
              {
                Messager::message()->addMessage("Successfully save  url file");
              }else{
                Messager::message()->addError("Failed to save url file");
              }
            }
        }
    }

}