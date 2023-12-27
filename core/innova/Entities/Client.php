<?php

namespace Innova\Entities;

class Client
{
    public function getUserAgent() {
        return $_SERVER['HTTP_USER_AGENT'];
    }

    public function getIPAddress(): mixed
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    public function getBrowser(): mixed
    {
        $userAgent = $this->getUserAgent();
        $browser = get_browser($userAgent, true);
        return $browser['browser'];
    }

    public function getOperatingSystem(): mixed
    {
        $userAgent = $this->getUserAgent();
        $browser = get_browser($userAgent, true);
        return $browser['platform'];
    }

    public function getDeviceType(): mixed
    {
        $userAgent = $this->getUserAgent();
        $browser = get_browser($userAgent, true);
        return $browser['device_type'];
    }

    public function getScreenResolution(): mixed
    {
        if (isset($_COOKIE['screen_resolution'])) {
            return $_COOKIE['screen_resolution'];
        }
        return 'Not available';
    }

    public function getTimezone(): mixed
    {
        if (isset($_COOKIE['client_timezone'])) {
            return $_COOKIE['client_timezone'];
        }
        return 'Not available';
    }

    public function getHeaders(): false|array
    {
        $headers = getallheaders();
        return $headers;
    }
}
