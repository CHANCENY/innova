<?php
namespace Innova\Controller\routers\files\src;

use Innova\Databases\Delete;
use Innova\Entities\FileSystem;
use Innova\modules\Files;
use Innova\modules\Messager;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class FileUpdates
{
    public function page(): mixed {
        if($this->updateFile()){
            Messager::message()->addMessage(
                "File updated successfully."
            );
            (new Request())->redirection("files/content");
        }
        return TemplatesHandler::view("files/update_file.php", isDefaultView: true);
    }

    /**
     * @return bool
     */
    private function updateFile(): bool
    {
        $req = new Request();
        if($req->method() === "post")
        {
            $file = new Files();
            $file->uploadFromForm("file");
            $fid = $file->finishUpload(3);

            if(is_numeric($fid)) {
                $newFile = FileSystem::load($fid);

                $oldFid = $req->get("fid");
                $oldFile = FileSystem::load($oldFid);

                //dd($oldFile);

                //copy new to old
                $oldFile->set("size", $newFile->getSize());
                $oldFile->set("width", $newFile->getWidth());
                $oldFile->set("height", $newFile->getHeight());
                $oldFile->set("extension", $newFile->getType());
                $oldFile->set("uri", $newFile->getUri());
                $oldFile->set("path", $newFile->getPath());

                if($oldFile->save()){
                    unlink($oldFile->getPath());
                    return Delete::delete("file_managed",['fid'=>$fid]);
                }
            }
        }
        return false;
    }
}