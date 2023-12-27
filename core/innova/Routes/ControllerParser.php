<?php

namespace Innova\Routes;


use Innova\Exceptions\ExceptionControllerPathNotDefined;
use Innova\modules\CurrentUser;
use Innova\request\Request;
use Innova\Templates\TemplatesHandler;

/**
 *
 */
class ControllerParser
{
  /**
   * @var mixed|null
   */
  private mixed $controller;
  /**
   * @var string
   */
  private string $controllerFunctions;
  /**
   * @var mixed|string
   */
  private mixed $namespace;
  /**
   * @var string
   */
  private string $navigation;
  /**
   * @var mixed
   */
  private mixed $template;
  /**
   * @var mixed|string
   */
  private mixed $controllerPath;
  /**
   * @var string
   */
  private string $head;
  /**
   * @var string
   */
  private string $footer;

  /**
   * @return mixed
   */
  public function getControllerPath(): mixed
  {
    return $this->controllerPath;
  }

  /**
   * @return string
   */
  public function getFooter(): string
  {
    return $this->footer;
  }

  /**
   * @return array
   */
  public function getRouteController(): array
  {
    return $this->routeController;
  }

  /**
   * @return string
   */
  public function getMiddler(): string
  {
    return $this->middler;
  }

  /**
   * @return string
   */
  public function getHead(): string
  {
    return $this->head;
  }

  /**
   * @return string
   */
  public function getTemplate(): mixed
  {
    return $this->template;
  }

  /**
   * @return string
   */
  public function getNavigation(): string
  {
    return $this->navigation;
  }

  /**
   * @return mixed
   */
  public function getNamespace(): mixed
  {
    return $this->namespace;
  }

  /**
   * @return mixed
   */
  public function getAccess(): mixed
  {
    return $this->access;
  }
  /**
   * @var array|mixed
   */
  private mixed $access;

  /**
   * @return string
   */
  public function getControllerFunctions(): string
  {
    return $this->controllerFunctions;
  }

  /**
   * @return mixed
   */
  public function getController(): mixed
  {
    return $this->controller;
  }

  /**
   * @return mixed
   */
  public function getMainEntry(): mixed
  {
    return $this->mainEntry;
  }
  /**
   * @var mixed|null
   */
  private mixed $mainEntry;

  /**
   * @param array $routeController
   * @param string $middler
   */
  public function __construct(private readonly array $routeController, private readonly string $middler = "")
  {
    $secure = (new SecureController($this->routeController));
  }

  /**
   * @return $this
   * @throws ExceptionControllerPathNotDefined
   */
  public function validateController(): ControllerParser
  {
    /**
     * User check against route access
     */
    $this->access = $this->routeController['access'] ?? [];
    $this->namespace = $this->routeController['namespace'] ?? "";
    $this->controllerPath = $this->routeController['module_path'] ?? "";
    if(empty($this->controllerPath))
    {
      throw new ExceptionControllerPathNotDefined("You controller path not found");
    }

    if(!is_dir($this->controllerPath))
    {
      throw new ExceptionControllerPathNotDefined("You controller directory not found");
    }

    //validate user and access view
    $this->controllerAccess();

    $list = explode("/", $this->controllerPath);
    $controllerDir = end($list);

    $infoFile = "$this->controllerPath/$controllerDir.info.json";
    $librariesFile = "$this->controllerPath/$controllerDir.libraries.json";
    $this->controllerFunctions = "$this->controllerPath/functions.php";
    if(file_exists($infoFile))
    {
      $data = $this->parseControllerInformation($infoFile, $this->routeController['id'] ?? "");
      $this->controller = $data['controller'] ?? null;
      $this->mainEntry = $data['entry_point'] ?? null;
    }else{
      throw new ExceptionControllerPathNotDefined("You controller info file not found");
    }

    if(file_exists($librariesFile))
    {
      global $head_section;
      global $footer_section;
      $js = $head_section['library']['js'] ?? [];
      $css = $head_section['library']['css'] ?? [];
      $data = $this->parseControllerLibraries($librariesFile, $this->routeController['id'] ?? "");
      $js = array_merge($js,$data['head']['js'] ?? []);
      $css = array_merge($css,$data['head']['css'] ?? []);
      $head_section['library']['js'] = $js;
      $head_section['library']['css'] = $css;

      $js = $footer_section['footer_outer']['library']['js'] ?? [];
      $css = $footer_section['footer_outer']['library']['css'] ?? [];
      $js = array_merge($js,$data['footer']['js'] ?? []);
      $css = array_merge($css,$data['footer']['css'] ?? []);
      $footer_section['footer_outer']['library']['js'] = $js;
      $footer_section['footer_outer']['library']['css'] = $css;
    }
    return $this;
  }

  /**
   * @return $this
   */
  public function navigationController(): ControllerParser
  {
    $defaultNav = "Innova\\Controller\\routers\\default_navigation\\src\\Navigation";
    $customNav = "Innova\\Controller\\Custom\\navigation\\src\\Navigation";
    $currentUser = new CurrentUser();

    if(class_exists($customNav) && !$currentUser->isAdmin())
    {
      $this->navigation = $customNav;
    }
    else
    {
      $this->navigation = $defaultNav;
    }
    return $this;
  }


  /**
   * @return $this
   */
  public function footerController(): ControllerParser
  {
    $defaultFooter = "Innova\\Controller\\routers\\default_footer\\src\\Footer";
    $customFooter = "Innova\\Controller\\Custom\\footer\\src\\Footer";
    $currentUser = new CurrentUser();

    if(class_exists($customFooter) && !$currentUser->isAdmin())
    {
      $this->footer = $customFooter;
    }
    else
    {
      $this->footer = $defaultFooter;
    }
    return $this;
  }

  /**
   * @return $this
   */
  public function buildHTMLTemplate(): ControllerParser
  {
    $contenType = $_SERVER['HTTP_CONTENT_TYPE'];
    $viewBuilder = new TemplatesHandler($this->middler, $this->controllerPath);

    $id = $this->routeController['id'] ?? "";

    if(str_contains($contenType, "html"))
    {
      $this->template ="<head>". PHP_EOL . $viewBuilder->buildHeadSection() ."</head>";
      $body =  $viewBuilder->buildBodySection();
      if(gettype($body) !== "string")
      {
        $this->template = $body;
      }else{
        $this->template .= "<body id='page-$id' class='body page--$id content-body'>" . $body;
        $this->template .= $viewBuilder->buildFooterSection() . "</body>";
      }
    }

    if(str_contains($contenType, "json"))
    {
      $this->template = $viewBuilder->buildJson();
    }
    return $this;

  }

  /**
   * @return void
   */
  public function controllerAccess(): void
  {
    $currentUser = new CurrentUser();
    $currentRoles = $currentUser->roles();
    if(empty($currentRoles))
    {
      $currentRoles[] = "anonymous";
    }
    if(empty($this->access))
    {
      $this->access[] = "admins";
    }
    // Find elements in array2 that are also in array1
    $commonElements = array_intersect($currentRoles, $this->access);

    if(empty($commonElements))
    {
      (new Request())->redirection("/errors/access-denied");
    }
  }

  /**
   * @param string $infoFile
   * @param string $id
   * @return array
   */
  public function parseControllerInformation(string $infoFile, string $id): array
  {
    if(file_exists($infoFile))
    {
      $content = json_decode(file_get_contents($infoFile), true);
      if(!empty($content))
      {
        foreach ($content as $key=>$item)
        {
          if(!empty($item['id']) && $item['id'] === $id)
          {
            return $item;
          }
        }
      }
    }
    return [];
  }

  /**
   * @param string $library
   * @param string $id
   * @return array
   */
  public function parseControllerLibraries(string $library, string $id): array
  {
    if(file_exists($library))
    {
      $content = json_decode(file_get_contents($library), true);
      if(!empty($content))
      {
        foreach ($content as $key=>$item)
        {
          if(!empty($item['id']) && $item['id'] === $id)
          {
            return $item;
          }
        }
      }
    }
    return [];
  }

}