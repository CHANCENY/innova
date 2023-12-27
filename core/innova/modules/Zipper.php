<?php

namespace Innova\modules;

use Innova\Exceptions\ZipperException;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ZipArchive;

class Zipper {
  private \ZipArchive $zip;

  /**
   * @param string $zipFile This zip file path if you want to extract zip then pass it name here when
   * you want to make zip pass the path of zip with to be created.
   *
   * @throws ZipperException
   */
  public function __construct(private readonly string $zipFile) {

    if(!str_ends_with($zipFile, ".zip")) {
      throw new ZipperException("Path given not a zip path");
    }
    $this->zip = new \ZipArchive();
  }

  /**
   * @param string $destination Where to put unzipped files.
   *
   * @return bool
   * True if unzipped.
   */
  public function extractFile(string $destination): bool
  {
    if($this->zip->open($this->zipFile) === true) {
      $result = $this->zip->extractTo($destination);
      $this->zip->close();
      return $result;
    }
    return false;
  }

  /**
   * @param array $files Array of file to be added to zip.
   *
   * @return bool
   * True if zipped.
   */
  public function compress(array $files): bool
  {
    $added = [];
    if($this->zip->open($this->zipFile) === true) {
      foreach ($files as $file) {
        if(file_exists($file)) {
          $added[] = $this->zip->addFile($file);
        }
        $this->zip->close();
      }
    }
    return !in_array(false, $added);
  }

  public function compressDirectory(string $sourceDirectory): string|null
  {

    // Define the name of the zip file to be created.
    $zipFileName = "sites/files/all/". random_int(0, 100).'_output.zip';

    // Create a ZipArchive instance.
    $zip = new ZipArchive();

    // Open the zip file for writing.
    if ($zip->open($zipFileName, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
      // Create a recursive directory iterator.
      $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($sourceDirectory),
        RecursiveIteratorIterator::SELF_FIRST
      );

      foreach ($files as $file) {
        // Skip . and .. directories.
        if (!$file->isDir()) {
          // Get the real and relative path for the file.
          $filePath = $file->getRealPath();
          $relativePath = substr($filePath, strlen($sourceDirectory) + 1);

          // Add the file to the zip archive.
          $zip->addFile($filePath, $relativePath);
        }
      }

      // Close the zip archive.
      $zip->close();
      return $zipFileName;
    }
    return null;
  }

  /**
   * @throws ZipperException
   */
  public static function extract(string $zipFile, $destination): bool
  {
    return (new static($zipFile))->extractFile($destination);
  }

  /**
   * @throws ZipperException
   */
  public static function create(array $files, $destination): bool
  {
    return (new static($destination))->compress($files);
  }
}