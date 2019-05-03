<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Widget;

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

        $minQuantity = $productQuantityStorageTransfer->getQuantityMin() ?? 1;
        $maxQuantity = $productQuantityStorageTransfer->getQuantityMax();
        $quantityInterval = $productQuantityStorageTransfer->getQuantityInterval() ?? 1;

        $this->addParameter('minQuantity', $minQuantity)
            ->addParameter('maxQuantity', $maxQuantity)
            ->addParameter('quantityInterval', $quantityInterval);
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductQuantityStorageTransfer
     */
    protected function getProductQuantityStorageTransfer(ProductViewTransfer $productViewTransfer): ProductQuantityStorageTransfer
    {
        $productQuantityStorageTransfer = $this->getFactory()
            ->getProductQuantityStorageClient()
            ->findProductQuantityStorage($productViewTransfer->getIdProductConcrete());

        if ($productQuantityStorageTransfer === null) {
            $productQuantityStorageTransfer = (new ProductQuantityStorageTransfer())
                ->setQuantityMin(1)
                ->setQuantityInterval(1);
        }

        return $productQuantityStorageTransfer;
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
