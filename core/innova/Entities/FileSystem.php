<?php

namespace Innova\Entities;

use Innova\Databases\Delete;
use Innova\Databases\Insert;
use Innova\Databases\Query;
use Innova\Databases\Select;
use Innova\Databases\Update;
use Innova\modules\Messager;

/**
 *
 */
class FileSystem
{
    private array $updatedFile;
    /**
     * @var array|false|null
     */
    private array|null|false $files;
    /**
     * @var true
     */
    private bool $isNew;
    private array $newFile;

    /**
     * @param int $fid
     */
    public function __construct(private readonly int $fid = -1)
    {
        $this->isNew = false;
        $this->newFile = [];
        $this->file();
    }

    /**
     * @return void
     */
    private function file(): void
    {

        $query = "SELECT * FROM file_managed";
        $this->files = [];
        if($this->fid > 0)
        {
            $query .= " WHERE fid = :id";
            $this->files = Query::query($query,['id'=>$this->fid])->getResult();
        }
        else{
            $query .= " ORDER BY changed DESC";
            $this->files = Query::query($query)->getResult();
        }
    }

    /**
     * @return bool|array|null
     */
    public function getFiles(): bool|array|null
    {
        return $this->files;
    }

    /**
     * @param int $fid
     * @return FileSystem
     */
    public static function load(int $fid = -1): FileSystem
    {
        return new FileSystem($fid);
    }

    /**
     * @param string|null $filename
     * @return $this
     */
    public function searchFile(string|null $filename): FileSystem
    {
       if(!empty($filename))
       {
           $results = [];
           foreach ($this->files as $value) {
               //dd($value['path']);
               if(strpos($value['path'],$filename)){
                   $results[] = $value;
               }
           }

           if(empty($results)){
              Messager::message()->addWarning("File $filename not found!");
           }
           $this->files = $results;
       }
       return $this;
    }

    /**
     * URI of image.
     * @return mixed|string
     */
    public function getUri(): mixed
    {
        return $this->files[0]['uri'] ?? "";
    }

    /**
     * Path of image.
     * @return mixed|string
     */
    public function getPath(): mixed
    {
        return $this->files[0]['path'] ?? "";
    }

    /**
     * Size of image.
     * @return int|mixed
     */
    public function getSize(): mixed
    {
        return $this->files[0]['size'] ?? 0;
    }

    /**
     * Extension of image.
     * @return mixed|string
     */
    public function getType(): mixed
    {
        return $this->files[0]['extension'] ?? "";
    }

    /**
     * Width of image.
     * @return int|mixed
     */
    public function getWidth(): mixed
    {
        return $this->files[0]['width'] ?? 0;
    }

    /**
     * Height of image.
     * @return mixed
     */
    public function getHeight(): mixed
    {
        return $this->files[0]['height'] ?? 0;
    }

    /**
     * @param string $key
     * @param int|string $value
     * @return FileSystem
     */
    public function set(string $key, int|string $value): FileSystem {
        if($this->isNew)
        {
            $this->newFile[$key] = $value;
        }else{
            $this->updatedFile[$key] = $value;
        }
        return $this;
    }

    /**
     * Saving file data.
     * @return bool|int
     * True if saved and fid if new.
     *
     */
    public function save(): bool|int {

        if($this->isNew) {
            $this->isNew = false;
            return Insert::insert(
                "file_managed",
                $this->newFile
            );
        }
        return Update::update(
            "file_managed",
            $this->updatedFile,
            [
                'fid'=> $this->fid
            ]
        );
    }

    /**
     * To create new instance of file.
     * @return $this
     */
    public function create():FileSystem {
        $this->isNew = true;
        $this->newFile = [
            'path'=> 'null',
            'uri' => 'null',
            'extension' => 'null',
            'size' => 0,
            'width' => 0,
            'height' => 0
        ];
        return $this;
    }

    public function delete(): bool {
        if(Delete::delete("file_managed",['fid'=>$this->fid]))
        {
            return unlink($this->getPath());
        }
        return false;
    }

    public function copyFile($old, $new): bool {

        $list1 = explode(".",$old);
        $list2 = explode(".", $new);
        if(strtolower(end($list1)) === strtolower(end($list2)))
        {
            return !empty(file_put_contents($old,file_get_contents($new)));
        }
        return !empty($this->transFormImages(
            $new,
            $old,
            end($list2),
            end($list1)
        ));
    }

    public function transFormImages(string $fromFile, string $toFile, string $from, string $to): bool|string {

        $allowed = ['jpg', 'png', 'jpeg'];
        if(in_array(strtolower($from), $allowed) && in_array(strtolower($to), $allowed))
        {
            if(strtolower($from) === "jpg" || strtolower($from) === "jpeg") {

                $image = imagecreatefromjpeg($fromFile);
                if ($image === false) {
                    return false;
                }

                if(strtolower($to) === "png") {
                    imagepng($image, $toFile. ".png",100);
                    imagedestroy($image);
                    return $toFile;
                }
                if(strtolower($to) === 'jpg'){
                    imagejpeg($image, $toFile.".jpg",100);
                    imagedestroy($image);
                    return $toFile;
                }
                if(strtolower($to) === 'jpeg'){
                    imagejpeg($image, $toFile.".jpeg",100);
                    imagedestroy($image);
                    return $toFile;
                }
                return false;
            }

            if(strtolower($from) === "png") {

                $image = imagecreatefrompng($fromFile);
                if ($image === false) {
                    return false;
                }

                if(strtolower($to) === "png") {
                    imagepng($image, $toFile. ".png",100);
                    imagedestroy($image);
                    return $toFile;
                }
                if(strtolower($to) === 'jpg'){
                    imagejpeg($image, $toFile.".jpg", 100);
                    imagedestroy($image);
                    return $toFile;
                }
                if(strtolower($to) === 'jpeg'){
                    imagejpeg($image, $toFile.".jpeg", 100);
                    imagedestroy($image);
                    return $toFile;
                }
                return false;
            }

            return false;
        }
    }

}