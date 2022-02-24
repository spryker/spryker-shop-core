<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductOfferWidget\ProductOfferWidgetConfig getConfig()
 * @method \SprykerShop\Yves\ProductOfferWidget\ProductOfferWidgetFactory getFactory()
 */
class ShoppingListProductOfferWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_IS_PRODUCT_OFFER_ACTIVE = 'isProductOfferActive';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addIsProductOfferActiveParameter($productViewTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ShoppingListProductOfferWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return $this
     */
    protected function addIsProductOfferActiveParameter(ProductViewTransfer $productViewTransfer)
    {
        if (!$productViewTransfer->getProductOfferReference()) {
            $this->addParameter(static::PARAMETER_IS_PRODUCT_OFFER_ACTIVE, null);

            return $this;
        }

        $productOfferStorageTransfer = $this->getFactory()
            ->getProductOfferStorageClient()
            ->findProductOfferStorageByReference(
                $productViewTransfer->getProductOfferReference(),
            );

        $this->addParameter(static::PARAMETER_IS_PRODUCT_OFFER_ACTIVE, $productOfferStorageTransfer !== null);

        return $this;
    }
}
