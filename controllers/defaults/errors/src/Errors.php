<?php
namespace Innova\Controller\routers\errors\src;


use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

class Errors
{

    private array|false $files;
    private \Throwable $throwable;
    private array $errors;
    /**
     * @var true
     */
    private bool $isView;

    public function __construct()
    {
        $this->isView = false;
        $this->errors = [];
        $request = new Request();
        $code = $request->get("error");
        $this->files = array_diff(
            scandir("sites/settings/application/errors"),
            ['.', '..', '...']
        );

        $this->deleteAll();

        if(!empty($code)) {
            $this->isView = true;
            if(!empty($this->files))
            {
                $code = $code . ".txt";
                $results = array_filter($this->files, function ($element) use ($code) {
                    return $element === $code;
                });

                $this->files = $results;
            }
        }

        if(!empty($this->files))
        {
            foreach ($this->files as $file)
            {
                 $this->throwable = unserialize(
                    base64_decode(
                        file_get_contents("sites/settings/application/errors/$file")
                    )
                );
                 $time = filemtime("sites/settings/application/errors/$file");
                 $list = explode(".", $file);
                $this->errors[] = [
                    'message'=>$this->throwable->getMessage() ?? null,
                    'file'=> $this->throwable->getFile() ?? null,
                    'record' => (new Request())->httpSchema() ."/errors/display?error=".  $list[0],
                    'trace' => $this->throwable->getTraceAsString(),
                    'line' => $this->throwable->getLine() ?? null,
                    'previous' => [
                        "file" =>  $this->throwable->getPrevious() ? $this->throwable->getPrevious()->getFile() : null,
                        "trace" =>  $this->throwable->getPrevious() ? $this->throwable->getPrevious()->getTraceAsString() : null,
                        "message" =>  $this->throwable->getPrevious() ? $this->throwable->getPrevious()->getMessage() : null,
                        "line" =>  $this->throwable->getPrevious() ? $this->throwable->getLine()->getFile() : null,
                    ],
                    'time' => $time
                ];
            }
        }
    }

    public function page(): mixed {
        if(!empty($this->errors)){
            usort($this->errors, function ($a, $b) {
                return $b['time'] <=> $a['time'];
            });
        }
        return TemplatesHandler::view(
            "errors/display_errors.php",
            [
                'errors'=> $this->errors,
                'view_details'=> $this->isView,
                'delete' => (new Request())->httpSchema() . "/errors/display?action=delete",
                'settings' => (new Request())->httpSchema() . "/settings/errors"
            ],
            isDefaultView: true
        );
    }

    public function deleteAll(): void
    {
        $request = new Request();
        if(!empty($request->get("action")))
        {
            foreach ($this->files as $file) {
                unlink("sites/settings/application/errors/$file");
            }
            $this->files = array_diff(
                scandir("sites/settings/application/errors"),
                ['.', '..', '...']
            );

            if(empty($this->files)){
                $request->redirection("/errors/display");
            }
        }
    }
}