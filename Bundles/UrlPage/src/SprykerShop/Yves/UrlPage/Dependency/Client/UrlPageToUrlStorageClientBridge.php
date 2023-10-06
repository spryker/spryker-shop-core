<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\UrlPage\Dependency\Client;

class UrlPageToUrlStorageClientBridge implements UrlPageToUrlStorageClientInterface
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
     * @param list<string> $urlCollection
     *
     * @return array<string, \Generated\Shared\Transfer\UrlStorageTransfer>
     */
    public function getUrlStorageTransferByUrls(array $urlCollection): array
    {
        return $this->urlStorageClient->getUrlStorageTransferByUrls($urlCollection);
    }
}
