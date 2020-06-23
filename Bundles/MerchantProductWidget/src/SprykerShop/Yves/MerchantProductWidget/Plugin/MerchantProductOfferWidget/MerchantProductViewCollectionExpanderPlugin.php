<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Plugin\MerchantProductOfferWidget;

use Generated\Shared\Transfer\MerchantProductViewCollectionTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin\MerchantProductViewCollectionExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantProductWidget\MerchantProductWidgetFactory getFactory()
 */
class MerchantProductViewCollectionExpanderPlugin extends AbstractPlugin implements MerchantProductViewCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Adds MerchantProductView to collection by the provided ProductView transfer object.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantProductViewCollectionTransfer
     */
    public function expand(
        MerchantProductViewCollectionTransfer $merchantProductViewCollectionTransfer,
        ProductViewTransfer $productViewTransfer
    ): MerchantProductViewCollectionTransfer {
        $merchantProductViewTransfer = $this->getFactory()->createMerchantProductReader()->findMerchantProductView($productViewTransfer, $this->getLocale());
        if ($merchantProductViewTransfer) {
            $merchantProductViewCollectionTransfer->addMerchantProductView($merchantProductViewTransfer);
        }

        return $merchantProductViewCollectionTransfer;
    }
}
