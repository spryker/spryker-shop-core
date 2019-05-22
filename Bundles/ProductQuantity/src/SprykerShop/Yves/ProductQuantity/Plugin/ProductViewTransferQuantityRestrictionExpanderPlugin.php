<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuantity\Plugin;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\ShoppingListPageExtension\Dependency\Plugin\ProductViewTransferExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductQuantity\ProductQuantityFactory getFactory()
 */
class ProductViewTransferQuantityRestrictionExpanderPlugin extends AbstractPlugin implements ProductViewTransferExpanderPluginInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expand(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        return $this->getFactory()
            ->getProductQuantityStorageClient()
            ->expandProductViewTransferWithQuantityRestrictions($productViewTransfer);
    }
}
