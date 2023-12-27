<?php

use Innova\Exceptions\InternalExecption;
use Innova\Middlewares\AppAuthentication;

function dd(...$data): void
{
  echo "<pre>";
  print_r($data);
  echo "</pre>";
  exit;
}

function active_controller(): \Innova\Routes\Routes
{
  global $route_object;
  return $route_object;
}


#[NoReturn] function exceptions(\Throwable $throwable): void
{
  /**
   * handle all exception happening in our site
   */
  $data = base64_encode(serialize($throwable));
  $file_name = "error_". random_int(0, 100000) . ".txt";
  if(!is_dir("sites/settings/application/errors")){
    mkdir("sites/settings/application/errors", 7777,true);
  }
  global $head_section;
  global $body_section;
  global $footer_section;

  $head_section = null;
  $body_section = null;
  $footer_section = null;

  file_put_contents("sites/settings/application/errors/$file_name", $data);
  echo "<p>Unexpected error encountered</p>";
  exit;
}

$errorSetting = \Innova\Configs\ConfigHandler::config("error_setting");
if(!empty($errorSetting['enabled']) && $errorSetting['enabled'] === "on")
{
  set_exception_handler('exceptions');
}

/**
 * @param $datetime
 * @param bool $full
 *
 * @return string
 * @throws Exception
 */
function ago($datetime, bool $full = false )
{
  $now = new \DateTime;
  $ago = new \DateTime($datetime);
  $diff = $now->diff($ago);

  $diff->w = floor($diff->d / 7);
  $diff->d -= $diff->w * 7;

  $string = array(
    'y' => 'year',
    'm' => 'month',
    'w' => 'week',
    'd' => 'day',
    'h' => 'hour',
    'i' => 'minute',
    's' => 'second',
  );
  foreach ($string as $k => &$v) {
    if ($diff->$k) {
      $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
    } else {
      unset($string[$k]);
    }
  }

  if (!$full) $string = array_slice($string, 0, 1);
  return $string ? implode(', ', $string) . ' ago' : 'just now';
}


function dateFormat(string $datetime, string $format): string
{
  try {
    return (new DateTime($datetime))->format($format);
  }catch (Throwable $throwable)
  {
    return $datetime;
  }
}


function changeGlobals(): array
{
  /**
   * Make func for change necessary
   */
  $scriptFilename = $_SERVER['SCRIPT_NAME'];
  $listingFileComponents = explode("/", $scriptFilename);
  $array = [];
  if ( count($listingFileComponents) >= 3 ) {
    $array['home'] = $listingFileComponents[1];
  }
  //TODO more changes
  return $array;
}


function formatBytes($bytes, $precision = 2): string
{
  $units = array('B', 'KB', 'MB', 'GB', 'TB');

  $bytes = max($bytes, 0);
  $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
  $pow = min($pow, count($units) - 1);
  $bytes /= (1 << (10 * $pow));

  return round($bytes, $precision) . $units[$pow];
}

function fullURI(string $uri = "/"): string
{
  $path = trim($uri, "/");
  $request = new \Innova\request\Request();
  $host = trim($request->httpSchema(), "/");
  return $host . "/" . $path;
}

function remove_spaces(string $text): string {
  $body = htmlspecialchars(strip_tags($text));
  $output = preg_replace('/\s+/', ' ', $body);
  if(gettype($output) === "string") {
    return $output;
  }
  return $output;
}

function truncateText($text, $maxLength = 200) {
  // Check if the text length is less than or equal to the maximum length
  if (strlen($text) <= $maxLength) {
    return $text; // No need to truncate
  }

  // Find the position of the first space after the maximum length
  $spacePosition = strpos($text, ' ', $maxLength);

  // If a space is found, truncate the text up to that space
  if ($spacePosition !== FALSE) {
    $truncatedText = substr($text, 0, $spacePosition);
  }
  else {
    // If no space is found after the maximum length, truncate at the maximum length
    $truncatedText = substr($text, 0, $maxLength);
  }

  return $truncatedText;
}
