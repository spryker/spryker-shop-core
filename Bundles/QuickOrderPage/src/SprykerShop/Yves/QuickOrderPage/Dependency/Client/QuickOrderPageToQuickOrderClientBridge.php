<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Dependency\Client;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;

class QuickOrderPageToQuickOrderClientBridge implements QuickOrderPageToQuickOrderClientInterface
{
    /**
     * @var \Spryker\Client\QuickOrder\QuickOrderClientInterface
     */
    protected $quickOrderClient;

    /**
     * @param \Spryker\Client\QuickOrder\QuickOrderClientInterface $quickOrderClient
     */
    public function __construct($quickOrderClient)
    {
        $this->quickOrderClient = $quickOrderClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    public function expandProductConcreteTransfer(ProductConcreteTransfer $productConcreteTransfer): ProductConcreteTransfer
    {
        return $this->quickOrderClient->expandProductConcreteTransfer($productConcreteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    public function getCurrentProductPriceTransfer(ItemTransfer $itemTransfer): CurrentProductPriceTransfer
    {
        return $this->quickOrderClient->getCurrentProductPriceTransfer($itemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    public function validateQuantityRestrictions(QuickOrderItemTransfer $quickOrderItemTransfer): ProductQuantityValidationResponseTransfer
    {
        return $this->quickOrderClient->validateQuantityRestrictions($quickOrderItemTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer[] $quickOrderItemTransfers
     *
     * @return \Generated\Shared\Transfer\ProductConcreteStorageTransfer[]
     */
    public function findProductConcretesByQuickOrderItemTransfers(array $quickOrderItemTransfers): array
    {
        return $this->quickOrderClient->findProductConcretesByQuickOrderItemTransfers($quickOrderItemTransfers);
    }
}
