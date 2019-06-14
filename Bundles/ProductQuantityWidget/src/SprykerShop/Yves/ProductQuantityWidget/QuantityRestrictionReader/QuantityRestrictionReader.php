<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityWidget\QuantityRestrictionReader;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use SprykerShop\Yves\ProductQuantityWidget\Dependency\Client\ProductQuantityWidgetToProductQuantityStorageClientInterface;

class QuantityRestrictionReader implements QuantityRestrictionReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ProductQuantityWidget\Dependency\Client\ProductQuantityWidgetToProductQuantityStorageClientInterface
     */
    protected $productQuantityStorageClient;

    /**
     * @param \SprykerShop\Yves\ProductQuantityWidget\Dependency\Client\ProductQuantityWidgetToProductQuantityStorageClientInterface $productQuantityStorageClient
     */
    public function __construct(
        ProductQuantityWidgetToProductQuantityStorageClientInterface $productQuantityStorageClient
    ) {
        $this->productQuantityStorageClient = $productQuantityStorageClient;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer
     */
    public function getQuantityRestrictions(int $idProductConcrete): ProductQuantityStorageTransfer
    {
        return $this->productQuantityStorageClient
            ->getProductQuantityStorage($idProductConcrete);
    }
}
