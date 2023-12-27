<?php

namespace Innova\modules;

use Innova\Databases\Select;

class Images {

  /**
   * Image info as in file_managed table.
   * @var array|mixed
   */
  private mixed $imageInfo;

  /**
   * SplInfo Class object of $image.
   * @var \SplFileInfo
   */
  private \SplFileInfo $splInfo;

  private string $processImage1;

  public function getImageInfo(): mixed {
    return $this->imageInfo;
  }

  public function getSplInfo(): \SplFileInfo {
    return $this->splInfo;
  }

  public function getProcessImage1(): string {
    return $this->processImage1;
  }

  /**
   * You can provide one of these if your are to use one but if you want to process
   * two images you can provide both fid and image path.
   *
   * @param int $fid Fid in file_managed
   * @param string $image Image path.
   */
  public function __construct(int $fid = 0, string $image = "") {
    if(!empty($fid)) {
      $this->imageInfo = Select::find("file_managed",['fid'=>$fid])[0] ?? [];
    }
    if(!empty($image) && file_exists($image)) {
      $this->splInfo = new \SplFileInfo($image);
    }
  }

  /**
   * @param int $type Type can be 1, 2 where 1 present use data from fid provided in construct
   * and 2 use data from image provided in construct
   * @param string $output_path
   *
   * @return \Innova\modules\Images
   */
  public function generateIconImage(int $type, string $output_path): Images
  {
    if($type === 1)
    {
      // Input image file (PNG or JPG)
      $inputImageFile = $this->imageInfo['path'] ?? null;
      // Load the image
      if(!empty($inputImageFile))
      {
        $image = $this->convert($inputImageFile);

        // Create a blank image for the ICO file
        $ico = imagecreatetruecolor(16, 16);

        // Fill the background with transparency
        imagefill($ico, 0, 0, imagecolorallocatealpha($ico, 0, 0, 0, 127));
        imagesavealpha($ico, true);

        // Copy the original image onto the ICO canvas
        imagecopyresampled($ico, $image, 0, 0, 0, 0, 16, 16, imagesx($image), imagesy($image));

        // Save the ICO file
        $path = "$output_path.png";
        // Save the ICO file as PNG
        imagepng($ico, $path);

        // Free up memory
        imagedestroy($image);
        imagedestroy($ico);

        // Convert PNG to ICO using ImageMagick (command line)
        exec("convert $path -define icon:auto-resize=16,32,48 $path.ico");

        // Remove the temporary PNG file
        unlink($path);
        $this->processImage1 = $path . ".ico";
      }

    }

    if($type === 2)
    {
      // Input image file (PNG or JPG)
      $inputImage = $this->splInfo->getRealPath();
      // Load the image
      if(!empty($inputImage))
      {
        $image = $this->convert($inputImage);

        // Create a blank image for the ICO file
        $ico = imagecreatetruecolor(16, 16);

        // Fill the background with transparency
        imagefill($ico, 0, 0, imagecolorallocatealpha($ico, 0, 0, 0, 127));
        imagesavealpha($ico, true);

        // Copy the original image onto the ICO canvas
        imagecopyresampled($ico, $image, 0, 0, 0, 0, 16, 16, imagesx($image), imagesy($image));

        // Save the ICO file
        $path = "$output_path.png";
        // Save the ICO file as PNG
        imagepng($ico, $path,0);

        // Free up memory
        imagedestroy($image);
        imagedestroy($ico);

        // Convert PNG to ICO using ImageMagick (command line)
        $output = $path . "_i.ico";
        exec("convert \"$path\" -density 300 -define icon:auto-resize=16,32,48 \"$output\"");
       // $output = shell_exec("convert \"$path\" -density 300 -define icon:auto-resize=16,32,48 \"$output\"");

        // Remove the temporary PNG file
       // unlink($path);
        $this->processImage1 = $output;
      }
    }

    return $this;
  }

  /**
   * @param string $path Image to convert
   *
   * @return false|\GdImage|null
   */
  private function convert(string $path): null|false|\GdImage {
      if(!empty($path)) {
        $list = explode(".", $path);
        $extension = end($list);

        return match (strtolower($extension)) {
          'png' => imagecreatefrompng($path),
          'jpg', 'jpeg' => imagecreatefromjpeg($path),
          'vif' => imagecreatefromavif($path),
          'gif' => imagecreatefromgif($path),
          'webp' => imagecreatefromwebp($path),
          default => imagecreatefromstring(file_get_contents($path)),
        };
      }
      return null;
  }
}