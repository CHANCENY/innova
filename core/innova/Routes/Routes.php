<?php
namespace Innova\Routes;

require_once "core/innova/Functions/innova_functions.php";
require_once  "core/innova/Variables/innova_variables.php";
require_once "sites/settings/application/settings.php";

use Innova\Configs\ConfigHandler;
use Innova\Exceptions\AccessDeniedException;
use Innova\Exceptions\ExceptionControllerNotExist;
use Innova\Exceptions\ParamsMissingException;
use Innova\Exceptions\RouteNotFoundException;
use Innova\Exceptions\RoutesCollectionMissingException;
use Innova\Middlewares\AppAuthentication;
use Innova\Middlewares\FormMiddleware;
use Innova\modules\CurrentUser;
use Innova\modules\Forms;
use Innova\request\Request;

class Routes
{
  private array $headers;
  private string $routeName;
  private string $routeUri;
  private array $controller;
  private array $params;

  private array $routesCollection;
  /**
   * @var true
   */
  private bool $routeMatch;
  /**
   * @var string[]
   */
  private array $matches;
  private bool $routingBlockerDisabled;
  private string $middleDirectory;

  /**
   * @return array
   */
  public function getHeaders(): array
  {
    return $this->headers;
  }

  /**
   * @return string
   */
  public function getRouteName(): string
  {
    return $this->routeName;
  }

  /**
   * @return string
   */
  public function getRouteUri(): string
  {
    return $this->routeUri;
  }

  /**
   * @return array
   */
  public function getController(): array
  {
    return $this->controller;
  }

  /**
   * @return array
   */
  public function getParams(): array
  {
    return $this->params;
  }

  /**
   * All route collection.
   * @return array
   */
  public function getRoutesCollection(): array
  {
    return $this->routesCollection;
  }

  /**
   * @return bool
   */
  public function isRouteMatch(): bool
  {
    return $this->routeMatch;
  }

  /**
   * @return array
   */
  public function getMatches(): array
  {
    return $this->matches;
  }

  /**
   * @return bool
   */
  public function isRoutingBlockerDisabled(): bool
  {
    return $this->routingBlockerDisabled;
  }

  /**
   * @return string
   */
  public function getMiddleDirectory(): string
  {
    return $this->middleDirectory;
  }

  public function __construct(private string $requestURI = "/")
  {
    $this->middleDirectory = "";
    if(empty($this->requestURI)){
      throw new RouteNotFoundException("Not Route Found");
    }
    $this->requestURI = parse_url($this->requestURI, PHP_URL_PATH);
    //check for ignore
    $this->routingBlockerDisabled = $this->ignoreChecker();

    // Use a regular expression to capture the part of the URI after the root folder
    if (preg_match("#/([^/]+)/(.+)#", $this->requestURI, $matches)) {
      // If you want the entire part after the root folder, use $matches[2]
      $request = new Request();
      $domain = $request->domain();
      $address = $_SERVER['SERVER_ADDR'];
      if($address == "::1" || $address === "127.0.0.1" || $domain === "localhost") {
        $this->middleDirectory = $matches[1] ?? "";
        global $middle;
        $middle = $this->middleDirectory;
        $this->requestURI = str_starts_with($matches[2], "/") ? $matches[2] : "/{$matches[2]}";
      }else{
        $this->requestURI = $matches[0];
      }
    }

    if(!$this->routingBlockerDisabled)
    {
      $this->routesCollectionHandler();
    }
  }


  public function app(bool $restrictionLevel = true): Routes
  {
    global $body_section;
    global $footer_section;
    //check if site is new
    $databaseConfig = ConfigHandler::config("database");
    if(empty($databaseConfig) && empty($_SESSION['installation_inprogress']))
    {
      $_SESSION['installation_inprogress'] = "yes";
      (new Request())->redirection("innova/installation",false,307);
    }

    if(!$this->routingBlockerDisabled)
    {
      if($this->matchRoute()->routeMatch){

        $this->appWarnings();
        /**
         * Since at this point we have route that correspond with request uri
         * we are going to do route checks
         */
        $this->validateHeaders()->paramsMapper();
        $controller = new ControllerParser($this->controller, $this->middleDirectory);
        $controller->validateController();
        $main = $controller->getMainEntry();
        $control = $controller->getController();
        $namespace = $controller->getNamespace();
        $className = "$namespace\\$control";
        if(class_exists($className))
        {
          //process main controller
          $controllerObject = new $className();
          $body_section['main'] = $controllerObject->$main();

          // Calling common.php file
          if(file_exists("views/common.php")) {
            require_once "views/common.php";
          }

          //process navigation controller
          $controller->navigationController();
          $controller->footerController();
          $navigation = $controller->getNavigation();
          $controllerNavigation = new $navigation();
          $buildNav = "navigationBuild";
          $body_section['navigation'] = $controllerNavigation->$buildNav();
          $footer  = $controller->getFooter();
          //dd($footer);
          $footerFooter = new $footer();
          $buildFooter = "footerBuild";
          $footer_section['footer_content'] = $footerFooter->$buildFooter();
        }
        else
        {
          throw new ExceptionControllerNotExist("Your Class $className does not exist");
        }

        $function = $controller->getControllerFunctions();
        if(file_exists($function))
        {
          $this->executeFunctions($function);
        }

        if(!$this->isRoutingBlockerDisabled())
        {
          //response
          $controller->buildHTMLTemplate();
          $typeResponse = $this->headers['content-type'] ?? null;
          header("Content-Type: $typeResponse");
          $content = $controller->getTemplate();

          if(gettype($content) === "string" && $typeResponse === "text/html")
          {
            print_r("<!DOCTYPE html><html lang='en'>". $content . "</html>");
            exit;
          }
          if($typeResponse === "application/json") {
            echo $content;
            exit;
          }
          print_r($content);
          exit;
        }

      }else{
        $this->isBasePath();
        throw new RouteNotFoundException("URI not found ({$this->requestURI})");
      }
    }
    return $this;
  }

  public function matchRoute() : Routes
  {
    $this->routeMatch = false;
    foreach ($this->routesCollection as $route => $routeObject) {
      $pattern = $routeObject['route_uri'];
      if (preg_match("#^$pattern$#", $this->requestURI, $matches)) {
        $this->headers = $routeObject['headers'];
        $this->routeName = $routeObject['route_name'];
        $this->params = $routeObject['params'];
        $this->controller = $routeObject['controller'];
        $this->routeUri = $routeObject['route_uri'];
        $this->routeMatch = true;
        array_shift($matches); // Remove the full match
        $this->matches = $matches;
        global $route_object;
        $route_object = $this;
        break;
      }
    }
    return $this;
  }

  private function routesCollectionHandler() : void
  {
    $path = "sites/settings/configs/config_routes.json";
    if(!file_exists($path))
    {
      throw new RoutesCollectionMissingException("Route collection config not found");
    }
    $this->routesCollection = json_decode(file_get_contents($path), true);
  }

  /**
   * @throws AccessDeniedException
   */
  private function validateHeaders(): Routes
  {
    $methods = $this->headers['method'];
    $methodRequest = $_SERVER['REQUEST_METHOD'];
    if($methodRequest === "POST" || $methodRequest === "PUT" || $methodRequest === "DELETE")
    {
      //Check csrf if form submitted
      $formMiddleware = new FormMiddleware();
      $formMiddleware->csrfValidation();
      /**
       * mandatory to check for all headers
       */
      if(!in_array($methodRequest, $methods))
      {
        (new Request())->redirection("errors/not-found",false,301);
      }

      /**
       * For GET request if content type not set default to text/html
       */
      $contentTypeRequest = $_SERVER['HTTP_CONTENT_TYPE'] ?? null;
      if(empty($contentTypeRequest))
      {
        $_SERVER['HTTP_CONTENT_TYPE'] = $this->headers['content-type'];
      }

    }

    /**
     * For GET request if content type not set default to text/html
     */
    $contentTypeRequest = $_SERVER['HTTP_CONTENT_TYPE'] ?? null;
    if($methodRequest === "GET" && empty($contentTypeRequest))
    {
      $_SERVER['HTTP_CONTENT_TYPE'] = $this->headers['content-type'];;
    }
    // Check the 'Origin' header
    $origin = $_SERVER['HTTP_ORIGIN'] ?? '';

    if($this->headers['cors'] === "allowed")
    {
      // Check if the request origin is allowed
      if(!empty($origin))
      {
        header('Access-Control-Allow-Origin: ' . $origin);
      }
      header("Access-Control-Allow-Methods: ".implode(",",$methods));
      header('Access-Control-Allow-Headers: Content-Type');
      header('Access-Control-Allow-Credentials: true');
      return $this;
    }

    $thisSiteDomain = isset($_SERVER['HTTPS']) ? "https" : "http";
    $thisSiteDomain .= "//".$_SERVER['HTTP_HOST'];
    if($thisSiteDomain === $origin)
    {
      // Check if the request origin is allowed
      header('Access-Control-Allow-Origin: ' . $origin);
      header("Access-Control-Allow-Methods: ".implode(",",$methods));
      header('Access-Control-Allow-Headers: Content-Type');
      header('Access-Control-Allow-Credentials: true');
    }else{
      throw new AccessDeniedException("You are not Allowed to Access this site");
    }

    return $this;
  }

  private function paramsMapper() : void
  {
    if(!empty($this->params['keys'])){
      $params = array_combine($this->params['keys'], $this->matches);
      if(empty($params))
      {
        throw new ParamsMissingException("Params passed in request dont meet the requirement require count (".count($this->params['keys']). ")");
      }
      foreach ($params as $key=>$value)
      {
        if(is_numeric($value))
        {
          $params[$key] = str_contains($value, ".") ? floatval($value) : intval($value);
        }
      }
      $_GET = $params;
    }
  }

  private function executeFunctions(string $function): void
  {
    global $head_section;
    global $body_section;
    global $footer_section;

    $data = file_get_contents($function);
    if(!empty($data))
    {
      require_once $function;
      $list = explode("/", $function);
      $controller = $list[count($list) - 2];

      //functions in functions.php
      if(is_callable($controller."_head_sections"))
      {
        $name = $controller."_head_sections";
        $name($head_section);
      }

      if(is_callable($controller."_body_sections"))
      {
        $name = $controller."_body_sections";
        $name($body_section);
      }

      if(is_callable($controller."_footer_sections"))
      {
        $name = $controller."_footer_sections";
        $name($footer_section);
      }
    }
  }

  private function ignoreChecker(): bool
  {
    /**
     * we need to ignore all direct files for assets
     */
    $list = explode("/", $this->requestURI);
    $filename = end($list);
    $list = explode(".", $filename);
    if(count($list) >= 1)
    {
      $extension = end($list);
      $installedExtension = ConfigHandler::config("file_ignore");
      if(!empty($installedExtension))
      {
        $found = $installedExtension[strtolower($extension)] ?? null;
        if(empty($found))
        {
          return false;
        }
        if($found['installed'] === true || $found['installed'] === 1)
        {
          return true;
        }
      }
    }
    return false;
  }

  private function isBasePath(): void {

    $request = new Request();
    $hostname = $request->domain();
    $httpSchema = $request->httpSchema();
    $home = trim($_SERVER['REQUEST_URI'], "/");
    if(!empty($home))
    {
      $home = "/$home/";
    }
    if(str_ends_with($httpSchema, $hostname))
    {

      $url = "home";
      $currentUser = new CurrentUser();
      $forms = new Forms();
      if($forms->siteIsNew())
      {
        $url = $home."innova/installation";
      }
      if(!empty($currentUser->isAdmin()))
      {
        $url = $home."innova/dashboard";
      }
      $flag = true;
      $config = ConfigHandler::config("home_page");
      if(!empty($config['home']) && empty($url)) {
        foreach ($this->routesCollection as $key=>$value) {
          if(!empty($value['controller']['id']) && $value['controller']['id'] === $config['home']){
            $url = $value['route_uri'];
            $flag = FALSE;
          }
        }
      }
      $url =  trim($url, "/");
      $this->requestURI = "/" .$url;
      $app = new Routes($this->requestURI);
      $app->app();
    }
  }

  public function appWarnings(): void {
      $app = new AppAuthentication();
      $app->settingWarning();
  }

  public function setTables(string $string): void {
    $this->controller['database']['database_tables_allowed'][] = $string;
  }

  public function setAllowed(string $string): void {
    $this->controller['database']['allowed_query'][] = $string;
  }

  public function setRouteName(string $name): void {
      $this->routeName = $name;
  }

  public function removeAction(): void {
      $this->controller['database']['allowed_query'] = [];
  }

  public function removeTables(): void {
    $this->controller['database']['database_tables_allowed'] = [];
  }
}