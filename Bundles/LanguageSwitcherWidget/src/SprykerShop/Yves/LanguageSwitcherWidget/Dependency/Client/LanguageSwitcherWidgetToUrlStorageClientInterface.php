<?php

namespace SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client;

interface LanguageSwitcherWidgetToUrlStorageClientInterface
{
    /**
     * @param string $url
     *
     * @return array
     */
    public function getUrlData($url);
}