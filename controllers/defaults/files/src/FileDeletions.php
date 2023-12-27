<?php
namespace Innova\Controller\routers\files\src;


use Innova\Entities\FileSystem;
use Innova\modules\Messager;
use Innova\request\Request;

class FileDeletions
{
    public function page(): mixed {
        $req = new Request();
        $fid = $req->get("fid", null);
        if(!empty($fid)) {
            $file = FileSystem::load($fid);
            if($file->delete()) {
                Messager::message()->addMessage(
                    "File deleted successfully."
                );
                $req->redirection("files/content");
            }
            Messager::message()->addWarning(
                "Failed to delete file this maybe caused by
                permissions of file system or database permissions."
            );
            $req->redirection("files/content");
        }
        Messager::message()->addError(
            "Error parameter to set."
        );
        $req->redirection("files/content");
        return "nothing";
    }
}