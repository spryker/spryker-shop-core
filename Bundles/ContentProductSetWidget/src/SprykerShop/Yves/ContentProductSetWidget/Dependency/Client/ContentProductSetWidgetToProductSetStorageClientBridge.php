<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductSetWidget\Dependency\Client;

class ContentProductSetWidgetToProductSetStorageClientBridge implements ContentProductSetWidgetToProductSetStorageClientInterface
{
    /**
     * @var \Spryker\Client\ProductSetStorage\ProductSetStorageClientInterface
     */
    protected $productSetStorageClient;

    /**
     * @param \Spryker\Client\ProductSetStorage\ProductSetStorageClientInterface $productSetStorageClient
     */
    public function __construct($productSetStorageClient)
    {
        $this->productSetStorageClient = $productSetStorageClient;
    }

    /**
     * @param int $idProductSet
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductSetDataStorageTransfer|null
     */
    public function getProductSetByIdProductSet($idProductSet, $localeName)
    {
        return $this->productSetStorageClient->getProductSetByIdProductSet($idProductSet, $localeName);
    }
}
