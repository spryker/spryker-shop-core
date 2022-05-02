<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StorageRouter\Dependency\Client;

class StorageRouterToUrlStorageClientBridge implements StorageRouterToUrlStorageClientInterface
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
     * @param string|null $localeName
     *
     * @return array<string, mixed>
     */
    public function matchUrl($url, $localeName): array
    {
        return $this->urlStorageClient->matchUrl($url, $localeName);
    }
}
