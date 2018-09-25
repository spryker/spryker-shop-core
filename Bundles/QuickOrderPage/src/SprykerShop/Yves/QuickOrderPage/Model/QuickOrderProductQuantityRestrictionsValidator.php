<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Model;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
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
        $productConcreteTransfer = $this->getProductConcreteTransfer($quickOrderItemTransfer);

        if (!$productConcreteTransfer->getIdProductConcrete()) {
            return $this->createValidationResponse(false);
        }

        $productQuantityStorageTransfer = $this->productQuantityStorageClient->findProductQuantityStorage($productConcreteTransfer->getIdProductConcrete());

        if ($productQuantityStorageTransfer === null) {
            return $this->createValidationResponse(true);
        }

        return $this->productQuantityClient->validateProductQuantityRestrictions(
            $quickOrderItemTransfer->getQty(),
            $this->createProductQuantityTransferFromProductQuantityStorageTransfer($productQuantityStorageTransfer)
        );
    }

    /**
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteTransfer
     */
    protected function getProductConcreteTransfer(QuickOrderItemTransfer $quickOrderItemTransfer): ProductConcreteTransfer
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())
            ->setSku($quickOrderItemTransfer->getSku());

        return $this->productClient->findProductConcreteIdBySku($productConcreteTransfer);
    }

    /**
     * @param bool $validity
     *
     * @return \Generated\Shared\Transfer\ProductQuantityValidationResponseTransfer
     */
    protected function createValidationResponse(bool $validity): ProductQuantityValidationResponseTransfer
    {
        return (new ProductQuantityValidationResponseTransfer())->setIsValid($validity);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer $productQuantityStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityTransfer
     */
    protected function createProductQuantityTransferFromProductQuantityStorageTransfer(ProductQuantityStorageTransfer $productQuantityStorageTransfer): ProductQuantityTransfer
    {
        return (new ProductQuantityTransfer())->fromArray(
            $productQuantityStorageTransfer->toArray(),
            true
        );
    }
}
