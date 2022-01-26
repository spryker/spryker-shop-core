<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductOfferShoppingListWidget\Widget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductOfferShoppingListWidget\ProductOfferShoppingListWidgetFactory getFactory()
 */
class ProductOfferShoppingListWidget extends AbstractWidget
{
    /**
     * @var string
     */
    protected const PARAMETER_PRODUCT = 'product';

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     */
    public function __construct(ProductViewTransfer $productViewTransfer)
    {
        $this->addParameter(static::PARAMETER_PRODUCT, $productViewTransfer);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductOfferShoppingListWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ProductOfferShoppingListWidget/views/product-offer-shopping-list/product-offer-shopping-list.twig';
    }
}
