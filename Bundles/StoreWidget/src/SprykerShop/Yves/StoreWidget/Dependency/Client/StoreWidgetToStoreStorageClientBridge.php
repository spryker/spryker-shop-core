<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StoreWidget\Dependency\Client;

class StoreWidgetToStoreStorageClientBridge implements StoreWidgetToStoreStorageClientInterface
{
    /**
     * @var \Spryker\Client\StoreStorage\StoreStorageClientInterface
     */
    protected $storeStorageClient;

    /**
     * @param \Spryker\Client\StoreStorage\StoreStorageClientInterface $storeStorageClient
     */
    public function __construct($storeStorageClient)
    {
        $this->storeStorageClient = $storeStorageClient;
    }

    /**
     * @return array<string>
     */
    public function getStoreNames(): array
    {
        return $this->storeStorageClient->getStoreNames();
    }
}
