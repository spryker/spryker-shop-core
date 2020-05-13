<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\ProductViewExpander;

use Generated\Shared\Transfer\ProductViewTransfer;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;

class ProductViewExpander implements ProductViewExpanderInterface
{
    /**
     * @see \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_CART_ADD
     */
    protected const URL_PATH_ADD_TO_CART = 'cart/add';
    protected const URL_PARAMETER_SKU = 'sku';

    /**
     * @var \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    protected $router;

    /**
     * @param \Symfony\Cmf\Component\Routing\ChainRouterInterface $router
     */
    public function __construct(ChainRouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    public function expandProductViewWithCartData(ProductViewTransfer $productViewTransfer): ProductViewTransfer
    {
        return $productViewTransfer->setAddToCartUrl($this->router->generate(
            static::URL_PATH_ADD_TO_CART,
            [static::URL_PARAMETER_SKU => $productViewTransfer->getSku()]
        ));
    }
}
