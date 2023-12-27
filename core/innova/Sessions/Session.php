<?php

namespace Innova\Sessions;

class Session
{
    /**
     * @param $name name can be any name as long as is string eg user you can also give multilevel name eg
     * user,uid this will result to ['user']['uid']
     * @param $value
     * @param $type public or private default is public
     * @return bool
     */
    public static function set(string $name, mixed $value, string $type = "public"): bool
    {
       $names = str_contains($name, ",") ? explode(",",$name) : $name;
       if(gettype($names) === "string")
       {
           $_SESSION[$type ?? "public"][$names] = $value;
           return isset($_SESSION[$type ?? "public"][$names]);
       }
       if(gettype($names) === "array")
       {
           $position = "";
           $names = array_filter($names, "strlen");
           foreach ($names as $key=>$name)
           {
               $position = &$_SESSION[$type ?? "public"][$name];
           }
           $position = $value;
           return !empty($position);
       }
       return false;
    }

    /**
     * @param string $name name can be any name as long as is string eg user you can also give multilevel name eg
     * user,uid this will result to ['user']['uid']
     * @param $type public or private default is public
     * @return mixed
     */
    public static function get(string $name, $type = "public"): mixed
    {
        $names = str_contains($name, ",") ? explode(",",$name) : $name;
        if(gettype($names) === "string")
        {
            return  $_SESSION[$type ?? "public"][$names] ?? NULL;
        }
        if(gettype($names) === "array")
        {
            $position = "";
            $names = array_filter($names, "strlen");
            foreach ($names as $key=>$name)
            {
                $position = &$_SESSION[$type ?? "public"][$name];
            }
            return $position ?? NULL;
        }
        return NULL;
    }

    /**
     * @param $name name can be any name as long as is string eg user you can also give multilevel name eg
     * user,uid this will result to ['user']['uid'] if empty $_SESSION will be unset
     * @param $type
     * @return bool
     */
    public static function clear($name = null, $type = "public"): bool
    {
        if(is_null($name))
        {
            unset($_SESSION);
            return isset($_SESSION);
        }
        $names = str_contains($name, ",") ? explode(",",$name) : $name;
        if(gettype($names) === "string")
        {
            unset($_SESSION[$type ?? "public"][$names]);
            return empty($_SESSION[$type ?? "public"][$names]);
        }
        if(gettype($names) === "array")
        {
            $position = "";
            $names = array_filter($names, "strlen");
            foreach ($names as $key=>$name)
            {
                $position = &$_SESSION[$type ?? "public"][$name];
            }
            unset($position);
            return empty($position);
        }
      return false;
    }
}