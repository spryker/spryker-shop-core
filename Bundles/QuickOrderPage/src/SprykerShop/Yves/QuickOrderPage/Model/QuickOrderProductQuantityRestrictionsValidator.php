<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Model;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductQuantityTransfer;
use Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductQuantityClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductQuantityStorageClientInterface;

class QuickOrderProductQuantityRestrictionsValidator implements QuickOrderProductQuantityRestrictionsValidatorInterface
{
    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductClientInterface
     */
    protected $productClient;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductQuantityClientInterface
     */
    protected $productQuantityClient;

    /**
     * @var \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductQuantityStorageClientInterface
     */
    protected $productQuantityStorageClient;

    /**
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductClientInterface $productClient
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductQuantityClientInterface $productQuantityClient
     * @param \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductQuantityStorageClientInterface $productQuantityStorageClient
     */
    public function __construct(
        QuickOrderPageToProductClientInterface $productClient,
        QuickOrderPageToProductQuantityClientInterface $productQuantityClient,
        QuickOrderPageToProductQuantityStorageClientInterface $productQuantityStorageClient
    ) {
        $this->productClient = $productClient;
        $this->productQuantityClient = $productQuantityClient;
        $this->productQuantityStorageClient = $productQuantityStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    public function validateQuantityRestrictions(QuickOrderItemTransfer $quickOrderItemTransfer): ProductQuantityValidationResponseTransfer
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setSku($quickOrderItemTransfer->getSku());

        $productConcreteTransfer = $this->productClient->findProductConcreteIdBySku($productConcreteTransfer);

        if (!$productConcreteTransfer->getIdProductConcrete()) {
            return (new ProductQuantityValidationResponseTransfer())->setIsValid(false);
        }

        $productQuantityStorageTransfer = $this->productQuantityStorageClient->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());

        if ($productQuantityStorageTransfer === null) {
            return (new ProductQuantityValidationResponseTransfer())->setIsValid(true);
        }

        $productQuantityTransfer = (new ProductQuantityTransfer())->fromArray(
            $productQuantityStorageTransfer->toArray(),
            true
        );

        return $this->productQuantityClient->validateProductQuantityRestrictions(
            $quickOrderItemTransfer->getQty(),
            $productQuantityTransfer
        );
    }
}
