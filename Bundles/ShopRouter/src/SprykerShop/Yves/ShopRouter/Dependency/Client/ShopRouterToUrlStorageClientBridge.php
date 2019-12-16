<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Dependency\Client;

/**
 * @deprecated Use `spryker-shop/storage-router` instead.
 */
class ShopRouterToUrlStorageClientBridge implements ShopRouterToUrlStorageClientInterface
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
     * @param string $localeName
     *
     * @return array
     */
    public function matchUrl($url, $localeName)
    {
        return $this->urlStorageClient->matchUrl($url, $localeName);
    }
}
