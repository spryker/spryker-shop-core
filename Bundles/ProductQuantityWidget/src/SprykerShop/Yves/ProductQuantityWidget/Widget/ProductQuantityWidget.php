<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantityWidget\Widget;

use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductQuantityWidget\ProductQuantityWidgetFactory getFactory()
 */
class ProductQuantityWidget extends AbstractWidget
{
    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     */
    public function __construct(ItemTransfer $itemTransfer)
    {
        $this->addParameter('quantity', $itemTransfer->getQuantity())
            ->addParameter('groupKey', $itemTransfer->getGroupKey())
            ->addParameter('sku', $itemTransfer->getSku());
        $this->setQuantityRestrictions($itemTransfer->getId());
    }

    /**
     * @param int $idProductConcrete
     *
     * @return void
     */
    protected function setQuantityRestrictions(int $idProductConcrete): void
    {
        $productQuantityStorageTransfer = $this->getFactory()
            ->createQuantityRestrictionReader()
            ->getQuantityRestrictions($idProductConcrete);

        $this->addParameter('productQuantityStorage', $productQuantityStorageTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductQuantityWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductQuantityWidget/views/product-quantity-widget/product-quantity-widget.twig';
    }
}
