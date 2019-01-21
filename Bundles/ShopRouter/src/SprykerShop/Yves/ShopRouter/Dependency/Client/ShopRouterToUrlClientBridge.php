<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopRouter\Dependency\Client;

class ShopRouterToUrlClientBridge implements ShopRouterToUrlStorageClientInterface
{
    /**
     * @var \Spryker\Client\Url\UrlClientInterface $urlStorageClient
     */
    protected $urlClient;

    /**
     * @param \Spryker\Client\Url\UrlClientInterface $urlStorageClient
     */
    public function __construct($urlStorageClient)
    {
        $this->urlClient = $urlStorageClient;
    }

    /**
     * @param string $url
     * @param string $localeName
     *
     * @return array
     */
    public function matchUrl($url, $localeName)
    {
        return $this->urlClient->matchUrl($url, $localeName);
    }
}
