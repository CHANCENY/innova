<?php

namespace Innova\request;

/**
 *
 */
class Request
{
    /**
     * @return string
     */
    public function protocal(): string
    {
        return isset($_SERVER['HTTPS']) ? "https" : "http";
    }

    /**
     * @return string
     */
    public function host(): string
    {
        return $_SERVER['HTTP_HOST'];
    }

    /**
     * @return string
     */
    public function httpSchema(): string
    {
        global $middle;
        if(empty($middle))
        {
            return $this->protocal() . "://" . $this->host();
        }
        return $this->protocal() . "://" . $this->host() . "/" . $middle;
    }

    /**
     * @return string
     */
    public function domain(): string
    {
        return $_SERVER['SERVER_NAME'] ?? "";
    }

    public function redirection($url, $replace = true, $redirectCode = 301): void
    {
        global $middle;
        $url = str_starts_with($url, "/") ? $url : "/". $url;
        if(!empty($middle))
        {
            $url = "/". $middle . $url;
        }
        header("Location: $url",$replace, $redirectCode);
        exit;
    }


    public function get($key, $defaults = null): mixed
    {
        if(!empty($_GET[$key]))
        {
            $value = htmlspecialchars(strip_tags($_GET[$key]));
            return is_numeric($value) ? str_contains($value, ".")  ? floatval($value) : intval($value) : $value;
        }
        return $defaults;
    }

    public function post($key, $defaults = null): mixed
    {
        $this->jsonParser();
        if(!empty($_POST[$key]) && gettype($_POST[$key]) !== "array")
        {
            return htmlspecialchars(strip_tags($_POST[$key]));
        }

        if(!empty($_POST[$key]) && gettype($_POST[$key]) === "array")
        {
          $values = [];
          foreach ($_POST[$key] as $key=>$value)
          {
            $values[$key] = htmlspecialchars(strip_tags($value));
          }
          return $values;
        }
        return $defaults;
    }

    public function file($key, $defaults = null): mixed
    {
        return $_FILES[$key] ?? $defaults;
    }

    public function method(): string
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }

    public function currentURI(): string
    {
        return $_SERVER['REQUEST_URI'];
    }

    private function jsonParser(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);
        if(!empty($data)) {
            foreach ($data as $key=>$value) {
                $_POST[$key] = $value;
            }
        }
    }
}