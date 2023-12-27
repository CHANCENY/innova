<?php

namespace Innova\modules;

use Innova\Configs\ConfigHandler;
use Innova\Databases\Insert;
use Innova\request\Request;

class Files
{
    const FINISHUPLOAD = [
        1 => "save-only",
        2 => "discard",
        3 => "save-record"
    ];
    private array $dimensions;
    private array $fileInTemp;

    /**
     * @return array
     */
    public function getFileInTemp(): array
    {
        return $this->fileInTemp;
    }

    public function __construct(
        private readonly string $destinationDirectory = "sites/files",
        private readonly int $allowedMaxSize = 200000000,
        private readonly array $allowedTypes = ["*"]
    )
    {
        $this->dimensions = [];
        $this->fileInTemp = [];
    }

    /**
     * @param $key
     * @return array contains size, path, uri, name, extension, error true or false
     */
    public function uploadFromForm($key): array
    {
        $files = (new Request())->file($key);
        if(!empty($files))
        {
            $names = $files['name'];
            $sizes = $files['size'];
            $tmps = $files['tmp_name'];
            if(gettype($names) === "array")
            {
                $total = count($names);
                for ($i = 0; $i < $total; $i++)
                {
                    $name = $names[$i];
                    $size = $sizes[$i];
                    $tmp = $tmps[$i];
                    if($size <= $this->allowedMaxSize)
                    {
                        $fullname = $this->inTemp($name, $tmp);
                        $info = new \SplFileInfo($fullname);
                        $extension = $info->getExtension();
                        $error = null;
                        switch (strtolower($extension))
                        {
                            case 'jpg':
                            case 'jpeg':
                            case 'png':
                                $error = $this->checkImage($fullname, $extension);
                                break;
                            case 'pdf':
                                $error = $this->checkPDF($fullname, $extension);
                                break;
                            case 'docs':
                            case 'doc':
                            case  'docx':
                                $error = $this->checkDOCS($fullname, $extension);
                                break;
                            default:
                                if(in_array("*",$this->allowedTypes)){
                                    $error = true;
                                }else{
                                    $error = false;
                                }
                        }
                        if($error)
                        {
                            $this->fileInTemp[] = [
                                'size'=>$info->getSize(),
                                'path'=>$fullname,
                                'uri'=> (new Request())->httpSchema() ."/".$fullname,
                                'extension'=> $info->getExtension(),
                                'width'=>$this->dimensions['width'] ?? 0,
                                'height'=>$this->dimensions['height']  ?? 0,
                                'error'=>false
                            ];
                        }
                    }
                }
            }

            if(gettype($names) === "string")
            {
                $fullname = $this->inTemp($names, $tmps);
                if(!empty($fullname))
                {
                    $info = new \SplFileInfo($fullname);
                    $extension = $info->getExtension();
                    $error = null;
                    switch (strtolower($extension))
                    {
                        case 'jpg':
                        case 'jpeg':
                        case 'png':
                            $error = $this->checkImage($fullname, $extension);
                            break;
                        case 'pdf':
                            $error = $this->checkPDF($fullname, $extension);
                            break;
                        case 'docs':
                        case 'doc':
                        case  'docx':
                            $error = $this->checkDOCS($fullname, $extension);
                            break;
                        default:
                            if(in_array("*",$this->allowedTypes)){
                                $error = true;
                            }else{
                                $error = false;
                            }
                    }

                    if($error)
                    {
                        $this->fileInTemp[] = [
                            'size'=>$info->getSize(),
                            'path'=>$fullname,
                            'uri'=> (new Request())->httpSchema() ."/".$fullname,
                            'extension'=> $info->getExtension(),
                            'width'=>$this->dimensions['width'] ?? 0,
                            'height'=>$this->dimensions['height']  ?? 0,
                            'error'=>false
                        ];
                    }
                }
            }
        }
        return $this->fileInTemp;
    }

  /**
   * Url File from url.
   * @param string $url File url
   *
   * @return array
   */
  public function uploadFromUrl(string $url): array
  {
    if(!empty($url))
    {
      $permanentName = null;
      $fileName = random_int(0, 100);
      $list = explode("/", $url);
      $extension = end($list);
      $fileName = $fileName. "." . $extension;
      $fileName = str_replace(["/", " ","?", "="], ["-", "-", "-", "-"], $fileName);
      $newFile = $this->inTemp($fileName, $url, true);
      if(!empty($newFile))
      {
        $type = mime_content_type($newFile);
        if(!empty($type))
        {
          $list = explode("/", $type);
          $permanentName = substr($newFile, strlen($newFile) - 5).'-'. random_int(0, 100) . "." . end($list);
          $permanentName = str_replace(["/", " ","?", "="], ["-", "-", "-", "-"], $permanentName);
          $permanentName = $this->inTemp($permanentName, $newFile, true);
          if(!file_exists($permanentName)) {
            $permanentName = null;
          }
          else
          {
            unlink($newFile);
          }
        }

        if(!empty($permanentName) && file_exists($permanentName))
        {
          $splInfo = new \SplFileInfo($permanentName);

          if($splInfo->getSize() <= $this->allowedMaxSize) {

            $error = null;
            switch (strtolower($splInfo->getExtension()))
            {
              case 'jpg':
              case 'jpeg':
              case 'png':
                $error = $this->checkImage($permanentName, $extension);
                break;
              case 'pdf':
                $error = $this->checkPDF($permanentName, $extension);
                break;
              case 'docs':
              case 'doc':
              case  'docx':
                $error = $this->checkDOCS($permanentName, $extension);
                break;
              default:
                if(in_array("*",$this->allowedTypes)){
                  $error = true;
                }else{
                  $error = false;
                }
            }

            if($error)
            {
              $this->fileInTemp[] = [
                'size'=>$splInfo->getSize(),
                'path'=>$permanentName,
                'uri'=> (new Request())->httpSchema() ."/".$permanentName,
                'extension'=> $splInfo->getExtension(),
                'width'=>$this->dimensions['width'] ?? 0,
                'height'=>$this->dimensions['height']  ?? 0,
                'error'=>false
              ];
            }
          }
        }
      }
    }
    return $this->fileInTemp;
  }

    public function checkImage($fullname, $extension): bool
    {
        // Use getimagesize to check the file
        $image_info = getimagesize($fullname);
        if(isset($image_info['mime']))
        {
            $list = explode("/", $image_info['mime']);
            if(in_array(strtolower(end($list)), $this->allowedTypes) || in_array("*", $this->allowedTypes))
            {
                $dim = $image_info[3] ?? "";
                // Use regular expressions to extract the numerical values for width and height
                if (preg_match('/width="(\d+)" height="(\d+)"/', $dim, $matches)) {
                    $this->dimensions['width'] = $matches[1];
                    $this->dimensions['height'] = $matches[2];
                }
                return true;
            }
        }
        return false;
    }

    public function checkPDF($fullname, $extension): bool
    {
        $file_content = file_get_contents($fullname);
        if (strpos($file_content, '%PDF') === 0) {
            return true;
        }
        return false;
    }

    public function checkDOCS($fullname, $extension): bool
    {
        $file_content = file_get_contents($fullname);
        if (substr($file_content, 0, 4) === 'PK' && strpos($file_content, 'word/document.xml') !== false) {
            return true;
        }
        return false;
    }

    private function inTemp($name, $tmp, bool $type = false): string
    {
        $tempStore = $this->destinationDirectory ."/" . "temp";
        if(!is_dir($tempStore))
        {
            mkdir($tempStore, 0777,true);
        }
        $fullName = $tempStore . "/" . $name;
        if(file_exists($fullName))
        {
            $fullName = $tempStore . "/" . random_int(0, 100) . $name;
        }

        if($type === false)
        {
          if(move_uploaded_file($tmp, $fullName))
          {
            return $fullName;
          }
        }else{
          $content = file_get_contents($tmp);
          if(!empty($content) && file_put_contents($fullName, $content))
          {
            return $fullName;
          }
        }

        return "";
    }

    /**
     * @param int $action find a valid key using constant FINISHUPLOAD
     * @return mixed file uri will be return if its one file or files uris will be return if multiple file
     * or fid will be return if fINISHUPLOAD is set to 3 and true or false will be return if fINISHUPLOAD is set to
     * 2
     */
    public function finishUpload(int $action = 1): mixed
    {
        $deleted = [];
        if(!empty($this->fileInTemp))
        {
            $files = [];
            foreach ($this->fileInTemp as $key=>$value)
            {
                $oldPath = $value['path'];
                if(self::FINISHUPLOAD[$action] === "discard")
                {
                    if(file_exists($oldPath))
                    {
                        $deleted[] = unlink($oldPath);
                    }
                }
                else
                {
                    $value['path'] = str_replace("temp", "all", $value['path']);
                    if(!is_dir($this->destinationDirectory . "/" . "all"))
                    {
                        mkdir($this->destinationDirectory . "/" . "all", 0777, true);
                    }

                    if(file_exists($value['path']))
                    {
                        $list = explode("/", $value['path']);
                        up:
                        $filename = implode("/", array_slice($list,0, count($list) - 1)). "/" . random_int(0, 100000). "_" . end($list);
                        if(file_exists($filename))
                        {
                            goto up;
                        }
                        $value['path'] = $filename;
                    }

                    $list = explode("/", $value['path']);
                    $line = implode("/", array_slice($list, array_search("sites", $list), count($list) - 1));
                    $value['uri'] = (new Request())->httpSchema(). "/" . $line . "/" . end($list);
                    $this->fileInTemp[$key] = $value;

                    if(self::FINISHUPLOAD[$action] === "save-only")
                    {
                        if(rename($oldPath, $value['path']))
                        {
                            if(count($this->fileInTemp) === 1)
                            {
                                return $value['uri'];
                            }
                            $files[] = $value['uri'];
                        }
                    }

                    if(self::FINISHUPLOAD[$action] === "save-record")
                    {
                        if(rename($oldPath, $value['path']))
                        {
                            if(count($this->fileInTemp) === 1)
                            {
                                if(!empty(ConfigHandler::config("database")))
                                {
                                    $fileFinal = $value;
                                    unset($fileFinal['error']);
                                    return Insert::insert("file_managed",$fileFinal);
                                }else{
                                    return $value['uri'];
                                }
                            }else{
                                $fileFinal = $value;
                                unset($fileFinal['error']);
                                $files[] = Insert::insert("file_managed",$fileFinal);
                            }
                        }
                    }
                }
            }
            return $files;
        }
        return in_array(true, $deleted);
    }

    /**
     * @param string $dir
     * @return bool
     */
    public static function removeDirectory(string $dir): bool
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object)) {
                        self::removeDirectory($dir . "/" . $object);
                    } else {
                        unlink($dir . "/" . $object);
                    }
                }
            }
            return rmdir($dir);
        }
        return false;
    }

}