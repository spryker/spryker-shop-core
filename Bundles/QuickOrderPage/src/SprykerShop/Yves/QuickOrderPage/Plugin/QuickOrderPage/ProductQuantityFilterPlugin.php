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

/**
 * @deprecated Now OrderItemTransfer is adjusted in Spryker\Client\QuickOrder\QuickOrderClient::buildQuickOrderTransfer.
 * Plugin will be removed in next module major release.
 *
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageFactory getFactory()
 */
class ProductQuantityFilterPlugin extends AbstractPlugin implements QuickOrderItemFilterPluginInterface
{
    /**
     * {@inheritDoc}
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
        if (!$quickOrderItemTransfer->getSku()) {
            return $quickOrderItemTransfer;
        }

        $idProduct = $this->getFactory()
            ->createProductResolver()
            ->getIdProductBySku($quickOrderItemTransfer->getSku());

        if (!$idProduct) {
            return $quickOrderItemTransfer;
        }

        $nearestQuantity = $this->getFactory()
            ->getProductQuantityStorageClient()
            ->getNearestQuantity($idProduct, (int)$quickOrderItemTransfer->getQuantity());
        $quickOrderItemTransfer->setQuantity($nearestQuantity);

        return $quickOrderItemTransfer;
    }
}
