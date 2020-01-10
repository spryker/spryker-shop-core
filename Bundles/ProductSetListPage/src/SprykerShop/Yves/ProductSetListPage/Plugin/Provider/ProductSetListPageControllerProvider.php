<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSetListPage\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

/**
 * @deprecated Use `\SprykerShop\Yves\ProductSetListPage\Plugin\Router\ProductSetListPageRouteProviderPlugin` instead.
 */
class ProductSetListPageControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_PRODUCT_SETS = 'product-sets';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addProductSetListRoute();
    }

    /**
     * @return $this
     */
    protected function addProductSetListRoute()
    {
        $this->createController('/{sets}', self::ROUTE_PRODUCT_SETS, 'ProductSetListPage', 'List', 'index')
            ->assert('sets', $this->getAllowedLocalesPattern() . 'product-sets|product-sets')
            ->value('sets', 'product-sets');

        return $this;
    }
}
