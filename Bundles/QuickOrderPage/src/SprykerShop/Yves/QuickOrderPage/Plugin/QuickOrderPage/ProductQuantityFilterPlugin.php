<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage\Plugin\QuickOrderPage;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Generated\Shared\Transfer\QuickOrderItemTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\QuickOrderPageExtension\Dependency\Plugin\QuickOrderItemFilterPluginInterface;

class ProductQuantityFilterPlugin extends AbstractPlugin implements QuickOrderItemFilterPluginInterface
{
    /**
     * {@inheritdoc}
     * - Adjusts quantity to the nearest possible value according product quantity restrictions.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\QuickOrderItemTransfer $quickOrderItemTransfer
     * @param \Generated\Shared\Transfer\ProductConcreteTransfer $productConcreteTransfer
     *
     * @return \Generated\Shared\Transfer\QuickOrderItemTransfer
     */
    public function filterItem(QuickOrderItemTransfer $quickOrderItemTransfer, ProductConcreteTransfer $productConcreteTransfer): QuickOrderItemTransfer
    {
        $productQuantityTransfer = $productConcreteTransfer->getProductQuantity();
        if (!$productQuantityTransfer) {
            return $quickOrderItemTransfer;
        }

        $quantity = (int)$quickOrderItemTransfer->getQuantity();
        $min = $productQuantityTransfer->getQuantityMin();
        $max = $productQuantityTransfer->getQuantityMax();
        $interval = $productQuantityTransfer->getQuantityInterval();

        if ($quantity < $min) {
            $quickOrderItemTransfer->setQuantity($quantity);

            return $quickOrderItemTransfer;
        }

        if ($max && $quantity > $max) {
            $quantity = $max;
        }

        if ($interval && ($quantity - $min) % $interval !== 0) {
            $quantity = round(($quantity - $min) / $interval) * $interval + $min;
        }

        $quickOrderItemTransfer->setQuantity($quantity);

        return $quickOrderItemTransfer;
    }
}
