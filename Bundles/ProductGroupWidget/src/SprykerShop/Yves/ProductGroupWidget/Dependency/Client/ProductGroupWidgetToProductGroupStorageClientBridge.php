<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidget\Dependency\Client;

use Generated\Shared\Transfer\ProductAbstractGroupStorageTransfer;
use Spryker\Client\ProductGroupStorage\ProductGroupStorageClientInterface;

class ProductGroupWidgetToProductGroupStorageClientBridge implements ProductGroupWidgetToProductGroupStorageClientInterface
{

    /**
     * @var ProductGroupStorageClientInterface
     */
    protected $productGroupStorageClient;

    /**
     * @param ProductGroupStorageClientInterface $productGroupStorageClient
     */
    public function __construct($productGroupStorageClient)
    {
        $this->productGroupStorageClient = $productGroupStorageClient;
    }

    /**
     * @param int $idProductAbstract
     * @param string $localeName
     *
     * @return ProductAbstractGroupStorageTransfer
     */
    public function findProductGroupItemsByIdProductAbstract($idProductAbstract, $localeName)
    {
        return $this->productGroupStorageClient->findProductGroupItemsByIdProductAbstract($idProductAbstract, $localeName);
    }

}
