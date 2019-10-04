<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductNewPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\ProductNewPage\Plugin\Router\ProductNewPageRouteProviderPlugin` instead.
 */
class ProductNewPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_NEW_PRODUCTS = 'new-products';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addNewProductsRoute();
    }

    /**
     * @return $this
     */
    protected function addNewProductsRoute()
    {
        $this->createController('/{newProducts}{categoryPath}', self::ROUTE_NEW_PRODUCTS, 'ProductNewPage', 'NewProducts', 'index')
            ->assert('newProducts', $this->getAllowedLocalesPattern() . 'new|new')
            ->value('newProducts', 'new')
            ->assert('categoryPath', '\/.+')
            ->value('categoryPath', null)
            ->convert('categoryPath', function ($categoryPath) {
                return preg_replace('#^\/' . $this->getAllowedLocalesPattern() . '#', '/', $categoryPath);
            });

        return $this;
    }
}
