<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityRestrictionWidget\QuantityRestrictionReader;

use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Client\ProductQuantityRestrictionWidgetToProductQuantityStorageClientInterface;
use SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Service\ProductQuantityRestrictionWidgetToProductQuantityServiceInterface;

class QuantityRestrictionReader implements QuantityRestrictionReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Client\ProductQuantityRestrictionWidgetToProductQuantityStorageClientInterface
     */
    protected $productQuantityStorageClient;

    /**
     * @var \SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Service\ProductQuantityRestrictionWidgetToProductQuantityServiceInterface
     */
    protected $productQuantityService;

    /**
     * @param \SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Client\ProductQuantityRestrictionWidgetToProductQuantityStorageClientInterface $productQuantityStorageClient
     * @param \SprykerShop\Yves\ProductQuantityRestrictionWidget\Dependency\Service\ProductQuantityRestrictionWidgetToProductQuantityServiceInterface $productQuantityService
     */
    public function __construct(
        ProductQuantityRestrictionWidgetToProductQuantityStorageClientInterface $productQuantityStorageClient,
        ProductQuantityRestrictionWidgetToProductQuantityServiceInterface $productQuantityService
    ) {
        $this->productQuantityStorageClient = $productQuantityStorageClient;
        $this->productQuantityService = $productQuantityService;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer
     */
    public function getQuantityRestrictions(int $idProductConcrete): ProductQuantityStorageTransfer
    {
        $productQuantityStorageTransfer = $this->productQuantityStorageClient
            ->findProductQuantityStorage($idProductConcrete);

        if ($productQuantityStorageTransfer === null) {
            return $this->createDefaultProductQuantityStorageTransfer();
        }

        return $this->prepareProductQuantityStorageTransfer($productQuantityStorageTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductQuantityStorageTransfer $productQuantityStorageTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer
     */
    protected function prepareProductQuantityStorageTransfer(ProductQuantityStorageTransfer $productQuantityStorageTransfer): ProductQuantityStorageTransfer
    {
        $minQuantity = $productQuantityStorageTransfer->getQuantityMin() ?? $this->getDefaultMinimumQuantity();
        $quantityInterval = $productQuantityStorageTransfer->getQuantityInterval() ?? $this->getDefaultInterval();

        return $productQuantityStorageTransfer->setQuantityMin($minQuantity)
            ->setQuantityInterval($quantityInterval);
    }

    /**
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer
     */
    protected function createDefaultProductQuantityStorageTransfer(): ProductQuantityStorageTransfer
    {
        return (new ProductQuantityStorageTransfer())
            ->setQuantityMin($this->getDefaultMinimumQuantity())
            ->setQuantityInterval($this->getDefaultInterval());
    }

    /**
     * @return float
     */
    protected function getDefaultMinimumQuantity(): float
    {
        return $this->productQuantityService->getDefaultMinimumQuantity();
    }

    /**
     * @return float
     */
    protected function getDefaultInterval(): float
    {
        return $this->productQuantityService->getDefaultInterval();
    }
}
