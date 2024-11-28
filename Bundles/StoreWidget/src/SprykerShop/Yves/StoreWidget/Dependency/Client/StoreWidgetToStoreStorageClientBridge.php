<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\StoreWidget\Dependency\Client;

use Generated\Shared\Transfer\StoreStorageTransfer;

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

    /**
     * @param string $name
     *
     * @return \Generated\Shared\Transfer\StoreStorageTransfer|null
     */
    public function findStoreByName(string $name): ?StoreStorageTransfer
    {
        return $this->storeStorageClient->findStoreByName($name);
    }
}
