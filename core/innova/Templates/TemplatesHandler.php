<?php

namespace Innova\Templates;

use Innova\Middlewares\FormMiddleware;
use Innova\Modifier\Modifier;
use Innova\modules\Site;
use Innova\request\Request;

/**
 *
 */
class TemplatesHandler {
    /**
     * @var string
     */
    private string $host;

    /**
     * @param string $middlerDirectory
     * @param string $controllerPath
     */
    public function __construct(private readonly string $middlerDirectory = "", private readonly string $controllerPath = "")
    {
        $request = new Request();
        $this->host = $request->protocal() . "://" . $request->host();
        if(!empty($this->middlerDirectory))
        {
            $this->host .=  "/" . $this->middlerDirectory;
        }

    }

    /**
     * @return string
     */
    public function buildHeadSection(): string
    {
        $data = null;
        $data .= $this->headTitle(). PHP_EOL;
        $data .= $this->buildLibrariesInHead(). PHP_EOL;
        $data .= $this->buildMetaTags();
        return $data;
    }

    /**
     * @return string
     */
    public function buildFooterSection(): string
    {
        return $this->buildLibrariesInFooter();
    }

    /**
     * @param string $type
     * @return string|null
     */
    public function headTitle(string $type = "template"): string|null
    {
        global $head_section;
        if(!empty($head_section['title']))
        {
            return "<title>{$head_section['title']}</title>";
        }
        return null;
    }

    /**
     * @return string
     */
    public function buildLibrariesInHead(): string
    {
        global $head_section;
        $css = "";
        $js = "";
        $generalAssets = "assets/assets.manifest.json";
        if(file_exists($generalAssets))
        {
            $data = json_decode(file_get_contents($generalAssets), true);
            if(str_starts_with($this->controllerPath, "controllers/defaults"))
            {
                if(!empty($data['defaults']['css']['head']))
                {
                    foreach ($data['defaults']['css']['head'] as $key=>$value)
                    {
                        $link = null;
                        if(str_starts_with($value, "http"))
                        {
                            $link = $value;
                        }else{
                            $link = $this->host. "/" . $value;
                        }
                        $css .= <<<CSS
<link rel="stylesheet" type="text/css" href="$link">
CSS . PHP_EOL;
                    }
                }
                if(!empty($data['defaults']['js']['head']))
                {
                    foreach ($data['defaults']['js']['head'] as $key=>$value)
                    {
                        $link = $this->host. "/" . $value;
                        $js .= <<<JS
<script src="$link"></script>
JS . PHP_EOL;
                    }
                }
            }
            else{
                if(!empty($data['custom']['css']['head']))
                {
                    foreach ($data['custom']['css']['head'] as $key=>$value)
                    {
                        $link = null;
                        if(str_starts_with($value, "http"))
                        {
                            $link = $value;
                        }else{
                            $link = $this->host. "/" . $value;
                        }
                        $css .= <<<CSS
<link rel="stylesheet" type="text/css" href="$link">
CSS . PHP_EOL;
                    }
                }
                if(!empty($data['custom']['js']['head']))
                {
                    foreach ($data['custom']['js']['head'] as $key=>$value)
                    {
                        $link = $this->host. "/" . $value;
                        $js .= <<<JS
<script src="$link"></script>
JS . PHP_EOL;
                    }
                }
            }
        }
        if(!empty($head_section['library']['css']))
        {
            foreach ($head_section['library']['css'] as $key=>$value)
            {
                $link = null;
                if(str_starts_with($value, "http"))
                {
                    $link = $value;
                }else{
                    $link = $this->host. "/" . $this->controllerPath. "/" . $value;
                }
                $css .= <<<CSS
<link rel="stylesheet" type="text/css" href="$link">
CSS . PHP_EOL;
            }
        }
        if(!empty($head_section['library']['js']))
        {
            foreach ($head_section['library']['js'] as $key=>$value)
            {
                $link = null;
                if(str_starts_with($value, "http"))
                {
                    $link = $value;
                }else{
                    $link = $this->host. "/" . $this->controllerPath. "/" . $value;
                }
                $js .= <<<JS
<script src="$link"></script>
JS . PHP_EOL;
            }
        }
        return $css. PHP_EOL . $js;
    }

    /**
     * @return string
     */
    public function buildLibrariesInFooter(): string
    {
        global $footer_section;
        $css = "";
        $js = "";
        $generalAssets = "assets/assets.manifest.json";
        if(file_exists($generalAssets))
        {
            $data = json_decode(file_get_contents($generalAssets), true);
            if(str_starts_with($this->controllerPath, "controllers/defaults"))
            {
                if(!empty($data['defaults']['css']['footer']))
                {
                    foreach ($data['defaults']['css']['footer'] as $key=>$value)
                    {
                        $link = null;
                        if(str_starts_with($value, "http"))
                        {
                            $link = $value;
                        }else{
                            $link = $this->host. "/" . $value;
                        }
                        $css .= <<<CSS
<link rel="stylesheet" type="text/css" href="$link">
CSS . PHP_EOL;
                    }
                }
                if(!empty($data['defaults']['js']['footer']))
                {
                    foreach ($data['defaults']['js']['footer'] as $key=>$value)
                    {
                        $link = $this->host. "/" . $value;
                        $js .= <<<JS
<script src="$link"></script>
JS . PHP_EOL;
                    }
                }
            }
            else
            {
              if(!empty($data['custom']['css']['footer']))
              {
                foreach ($data['custom']['css']['footer'] as $key=>$value)
                {
                  $link = null;
                  if(str_starts_with($value, "http"))
                  {
                    $link = $value;
                  }else{
                    $link = $this->host. "/" . $value;
                  }
                  $css .= <<<CSS
<link rel="stylesheet" type="text/css" href="$link">
CSS . PHP_EOL;
                }
              }
              if(!empty($data['custom']['js']['footer']))
              {
                foreach ($data['custom']['js']['footer'] as $key=>$value)
                {
                  $link = $this->host. "/" . $value;
                  $js .= <<<JS
<script src="$link"></script>
JS . PHP_EOL;
                }
              }
            }
        }
        if(!empty($footer_section['footer_outer']['library']['css']))
        {
            foreach ($footer_section['footer_outer']['library']['css'] as $key=>$value)
            {
                $link = null;
                if(str_starts_with($value, "http"))
                {
                    $link = $value;
                }else{
                    $link = $this->host. "/" . $this->controllerPath. "/" . $value;
                }
                $css .= <<<CSS
<link rel="stylesheet" type="text/css" href="$link">
CSS . PHP_EOL;
            }
        }
        if(!empty($footer_section['footer_outer']['library']['js']))
        {
            foreach ($footer_section['footer_outer']['library']['js'] as $key=>$value)
            {
                $link = null;
                if(str_starts_with($value, "http"))
                {
                    $link = $value;
                }else{
                    $link = $this->host. "/" . $this->controllerPath. "/" . $value;
                }
                $js .= <<<JS
<script src="$link"></script>
JS . PHP_EOL;
            }
        }
        return $css. PHP_EOL . $js;
    }


    /**
     * @return string
     */
    public function buildMetaTags(): string
    {
        global $head_section;
        $metaObject = $head_section['meta'] ?? [];
        $allMetaTags = null;
        foreach ($metaObject as $key=>$value)
        {
            switch($value['name'])
            {
                case 'author':
                case 'title':
                case 'description':
                case 'keywords':
                case  'image':
                case  'generator':
                case  'robots':
                case 'subject':
                case 'copyright':
                case 'language':
                case 'revised':
                case  'abstract':
                case  'topic':
                case  'summary':
                case 'Classification':
                case 'designer':
                case 'reply-to':
                case 'owner':
                case  'identifier-URL':
                case  'url':
                case  'directory':
                case 'pagename':
                case 'category':
                case 'coverage':
                case 'distribution':
                case  'rating':
                case  'revisit-after':
                case  'subtitle':
                case 'target':
                case 'HandheldFriendly':
                case 'MobileOptimized':
                case  'date':
                case  'search_date':
                case  'DC.title':
                case 'ResourceLoaderDynamicStyles':
                case 'medium':
                case 'syndication-source':
                case 'original-source':
                case  'pageKey':
                case  'y_key':
                case  'verify-v1':
                case 'msapplication-starturl':
                case 'msapplication-window':
                case 'msapplication-navbutton-color':
                case  'application-name':
                case  'msapplication-tooltip':
                case  'msapplication-task':
                case 'msvalidate.01':
                case 'news_keywords':
                case 'tweetmeme-title':
                case  'blogcatalog':
                case  'csrf-param':
                case  'csrf-token':
                    $allMetaTags .= $this->normalMeta($value['name'], $value['value']).PHP_EOL;
                    break;
                case 'og:locale':
                case 'og:type':
                case 'og:url':
                case 'og:image':
                case 'og:site_name':
                case 'og:description':
                case 'fb:page_id':
                case 'og:email':
                case 'og:phone_number':
                case 'og:fax_number':
                case 'og:latitude':
                case 'og:longitude':
                case 'og:street-address':
                case 'og:locality':
                case 'og:region':
                case 'og:postal-code':
                case 'og:country-name':
                case 'fb:admins':
                case 'og:audio:title':
                case 'og:points':
                case 'og:video':
                case 'og:video:height':
                case 'og:video:width':
                case 'og:video:type':
                case 'og:audio':
                case 'og:audio:artist':
                case 'og:audio:album':
                case 'og:audio:type':
                    $allMetaTags .= $this->ogMetaTags($value['name'], $value['value']).PHP_EOL;
                    break;
                case 'shortcut icon':
                case 'icon':
                    $allMetaTags .= $this->iconsMetaTags($value['name'], $value['value']) . PHP_EOL;

                default:

            }
        }
        $allMetaTags .= $this->requiredMetags();
        return $allMetaTags;
    }

    /**
     * @param $name
     * @param $value
     * @return string
     */
    private function normalMeta($name, $value): string{
        return "<meta name='$name' content='$value'/>".PHP_EOL;
    }

    /**
     * @return mixed
     */
    public function buildBodySection(): mixed
    {
        global $body_section;
        global $footer_section;
        $footer = $footer_section['footer_content'] ?? null;
        $main = $body_section['main'] ?? null;

        $navigation = $body_section['navigation'] ?? null;
        $navigation .=  gettype($main) === "array" ? "" : $main;
        $body = null;

        $controller = active_controller();
        $control = $controller->getController();
        $id = $control['id'] ?? "";

        if(gettype($main) !== "string")
        {
            return $main;
        }
        else{
            $body .= "<main class='main main-body container-main main-wrapper' id='main-body-container-$id'>$navigation</main>";
        }
        $body .= "<footer class='footer footer-body footer-body-container' id='footer-body-container-$id'>$footer</footer>";
        return str_replace("@CSRF",$this->buildCsrfToken(),$body);
    }

    /**
     * @return string
     */
    public function buildJson(): string
    {
        global $body_section;
        if(gettype($body_section['main']) === "array")
        {
            return json_encode($body_section['main']);
        }
        return $body_section['main'];
    }

    /**
     * @param string $viewName filename of your view by default this method will look for views in views directory
     * on root Note that if you have views/home/home.php you will have to specify home/home.php
     * @param mixed $data
     * @return mixed
     */
    public function render(string $viewName, mixed $data = "", $isDefaultView = false): mixed
    {
        $viewContent = "";
        if($isDefaultView)
        {
            $viewName = "core/views/" . $viewName;
        }
        else
        {
            $viewName = "views/" . $viewName;
        }

        if(file_exists($viewName))
        {
            ob_start();
            extract(gettype($data) === "array" ? $data : []);
            require_once $viewName;

            //view content
            $viewContent = ob_get_clean();
        }
        return $viewContent;
    }


    /**
     * @param string $viewName
     * @param mixed $data
     * @param $isDefaultView
     * @return mixed
     */
    public static function view(string $viewName, mixed $data = "", bool $isDefaultView = false): mixed
    {
        return (new static())->render($viewName, $data, $isDefaultView);
    }

    /**
     * @param $name
     * @param $value
     * @return string
     */
    private function ogMetaTags($name, $value)
    {
        return "<meta name='$name' content='$value'>".PHP_EOL;
    }

    /**
     * @return string
     */
    private function requiredMetags()
    {
      //icon metas
      $logo = Site::logo();
      $type = null;
      if(!empty($logo)) {
        $list = explode(".", $logo);
        $type = "image/".end($list);
      }

      //page title
      $title = active_controller()->getRouteName();

      //making canonical url
      $request = new Request();
$canonical = $request->httpSchema(). "/" . trim($request->currentURI(), '/');

      return <<<META
<meta charset="UTF-8">
<link rel="icon" type="$type" href="$logo">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
<link rel="canonical" href="$canonical">
<link rel="shortcut icon" href="$logo" type="$type">
<title>$title</title>

META;

    }

    private function iconsMetaTags(string $name, string $value)
    {
        if(!empty($name) && !empty($value))
        {
            $list = explode('.', $value);
            $extension = end($list);
            return "<link rel='$name' href='$value' type='image/$extension'>";
        }
    }

    public function buildCsrfToken(): string {
        $middleForm = new FormMiddleware();
        $token = $middleForm->csrf();
        if(!empty($token)) {
            return "<input type='hidden' name='csrf' value='$token'/>";
        }
        return "";
    }
}