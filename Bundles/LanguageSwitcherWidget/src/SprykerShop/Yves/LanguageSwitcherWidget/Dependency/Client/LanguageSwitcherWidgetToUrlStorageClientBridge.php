<?php

namespace SprykerShop\Yves\LanguageSwitcherWidget\Dependency\Client;

class LanguageSwitcherWidgetToUrlStorageClientBridge implements LanguageSwitcherWidgetToUrlStorageClientInterface
{
    /**
     * @var \Spryker\Client\UrlStorage\UrlStorageClientInterface
     */
    protected $urlStorageClient;

    /**
     * @param \Spryker\Client\UrlStorage\UrlStorageClientInterface $urlStorageClient
     */
    public function __construct($urlStorageClient)
    {
        $this->urlStorageClient = $urlStorageClient;
    }

    /**
     * @param string $url
     *
     * @return \Generated\Shared\Transfer\UrlTransfer|null
     */
    public function getUrlData($url)
    {
        return $this->urlStorageClient->getUrlData($url);
    }
}
