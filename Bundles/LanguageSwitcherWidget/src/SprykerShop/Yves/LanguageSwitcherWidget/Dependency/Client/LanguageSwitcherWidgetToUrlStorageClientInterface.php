<?php

namespace SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client;

interface LanguageSwitcherWidgetToUrlStorageClientInterface
{
    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function getUrlData($url);
}