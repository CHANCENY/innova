<?php

namespace Innova\modules;

use Innova\Configs\ConfigHandler;

/**
 * Build up the site information
 */
class Site
{
    /**
     * @var mixed
     */
    private mixed $siteInformation;

    /**
     * This is site
     */
    public function __construct()
    {
        $this->siteInformation = ConfigHandler::config("site_information");
    }

    /**
     * @return string|null
     */
    public function siteName(): string|null
    {
        return $this->siteInformation['name'] ?? null;
    }

    /**
     * @return string|null
     */
    public function siteEmail(): string|null
    {
        return $this->siteInformation['email'] ?? null;
    }

    /**
     * @return string|null
     */
    public function siteSlogan(): string|null
    {
        return $this->siteInformation['slogan'] ?? null;
    }

    /**
     * @return string|null
     */
    public function siteLogo(): string|null
    {
        return $this->siteInformation['logo'] ?? null;
    }

    /**
     * @return string|null
     */
    public function siteHome(): string|null
    {
        return $instance->siteInformation['home'] ?? null;
    }

    /**
     * @return string|null
     */
    public static function name(): string|null
    {
        return (new static())->siteName();
    }

    /**
     * @return string|null
     */
    public static function logo(): string|null
    {
        return (new static())->siteLogo();
    }

    /**
     * @return string|null
     */
    public static function email(): string|null
    {
        return (new static())->siteEmail();
    }

    /**
     * @return string|null
     */
    public static function home(): string|null
    {
        return (new static())->siteHome();
    }

    /**
     * @return string|null
     */
    public static function slogan(): string|null
    {
        return (new static())->siteSlogan();
    }
}