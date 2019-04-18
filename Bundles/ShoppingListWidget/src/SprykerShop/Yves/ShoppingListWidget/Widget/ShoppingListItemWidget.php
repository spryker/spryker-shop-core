<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Widget;

use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use Generated\Shared\Transfer\ProductQuantityStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class ShoppingListItemWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param bool $isItemAvailable
     */
    public function __construct(ProductViewTransfer $productViewTransfer, bool $isItemAvailable)
    {
        $this->addParameter('item', $productViewTransfer)
            ->addParameter('readOnly', !$isItemAvailable);
        $this->setQuantityRestrictions($productViewTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return void
     */
    protected function setQuantityRestrictions(ProductViewTransfer $productViewTransfer): void
    {
        $productQuantityStorageTransfer = $this->getProductQuantityStorageTransfer($productViewTransfer);
        $productConcreteAvailabilityTransfer = $this->getProductConcreteAvailabilityTransfer($productViewTransfer);
        $quantityRestrictionReader = $this->getFactory()
            ->createQuantityRestrictionReader();
        $minQuantity = $quantityRestrictionReader->getMinQuantity($productQuantityStorageTransfer);
        $maxQuantity = $quantityRestrictionReader->getMaxQuantity($productQuantityStorageTransfer, $productConcreteAvailabilityTransfer);
        $quantityInterval = $quantityRestrictionReader->getQuantityInterval($productQuantityStorageTransfer);

        $this->addParameter('minQuantity', $minQuantity)
            ->addParameter('maxQuantity', $maxQuantity)
            ->addParameter('quantityInterval', $quantityInterval);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer|null
     */
    protected function getProductQuantityStorageTransfer(ProductViewTransfer $productViewTransfer): ?ProductQuantityStorageTransfer
    {
        return $this->getFactory()
            ->getProductQuantityStorageClient()
            ->findProductQuantityStorage($productViewTransfer->getIdProductConcrete());
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer|null
     */
    protected function getProductConcreteAvailabilityTransfer(ProductViewTransfer $productViewTransfer): ?ProductConcreteAvailabilityTransfer
    {
        $productConcreteAvailabilityRequestTransfer = new ProductConcreteAvailabilityRequestTransfer();
        $productConcreteAvailabilityRequestTransfer->setSku($productViewTransfer->getSku());

        return $this->getFactory()
            ->getAvailabilityClient()
            ->findProductConcreteAvailability($productConcreteAvailabilityRequestTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShoppingListItemWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ShoppingListWidget/views/shopping-list-item/shopping-list-item.twig';
    }
}
