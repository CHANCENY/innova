<?php

namespace Innova\Modifier;

use Innova\request\Request;

/**
 * This class is for modifying global variables of page content
 * It is class if you want to modified some global keys eg title
 */

class Modifier
{
  /**
   *
   */
  const TYPE = [
    1 => "css",
    2 => "js"
  ];

  /**
   *
   */
  const SECTION = [
    1 => "head",
    2 => "footer"
  ];

  /**
   * @param string $title
   * @param bool $appendDomain
   * @param $joinBy
   * @return void
   */
  public static function setTitle(string $title, bool $appendDomain = false, $joinBy = " | "): void
  {
    global $head_section;
    $title = htmlspecialchars(strip_tags($title));
    if($appendDomain)
    {
      $title = $title . $joinBy . (new Request())->domain();
    }
    $head_section['title'] = $title;
  }

  /**
   * @return string
   * Page title
   */
  public static function getTitle():string {
    global $head_section;
    return $head_section['title'] ?? active_controller()->getRouteName();
  }

  /**
   * @param string $library
   * @param int $type
   * @param int $section
   * @return void
   */
  public static function setLibrary(string $library, int $type = 1, int $section = 1): void
  {
    global $head_section;
    global $footer_section;

    if(self::SECTION[$section] === "head")
    {
      if(self::TYPE[$type] === "js")
      {
        $head_section['library']['js'][] = $library;
      }
      if(self::TYPE[$type] === "css")
      {
        $head_section['library']['css'][] = $library;
      }
    }

    if(self::SECTION[$section] === "footer")
    {
      if(self::TYPE[$type] === "js")
      {
        $footer_section['footer_outer']['library']['js'][] = $library;
      }
      if(self::TYPE[$type] === "css")
      {
        $footer_section['footer_outer']['library']['css'][] = $library;
      }
    }
  }

  /**
   * @param string $name
   * @param $value
   * @return void
   */
  public static function setMetaTags(string $name, $value): void
  {
    global $head_section;
    $head_section['meta'][] = ['name'=>$name, "value"=>$value];
  }

  public static function addMenu(string $routeID, array $options = [], string $group = null, string $groupClass = null): bool {
    global $menus;

    $routes = active_controller()->getRoutesCollection();

    if(!empty($routes)) {
      foreach ($routes as $key=>$route) {
        $controller = $route['controller'];
        $id = $controller['id'];
        if(trim($id) === trim($routeID)) {
          $uri = $options['route_uri'] ?? $route['route_uri'];
          $text = $options['route_name'] ?? $route['route_name'];
          if(!empty($group)) {
            $menus["$group:$groupClass"][] = [
              'text'=> $text,
              'link' => fullURI($uri),
              'title' => $options['title'] ?? $text,
              'class' => $options['class'] ?? "menu-$routeID",
              'id' => $options['id'] ?? $routeID
            ];

            return true;
          }

          $menus[] = [
            'text'=> $text,
            'link' => fullURI($uri),
            'title' => $options['title'] ?? $text,
            'class' => $options['class'] ?? "menu-$routeID",
            'id' => $options['id'] ?? $routeID
          ];
          return true;
        }
      }
    }
    return false;
  }

  public static function menus() {
    global $menus;
    return $menus;
  }
}