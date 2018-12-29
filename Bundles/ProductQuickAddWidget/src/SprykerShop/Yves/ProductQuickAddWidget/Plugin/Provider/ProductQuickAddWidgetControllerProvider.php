<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductQuickAddWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class ProductQuickAddWidgetControllerProvider extends AbstractYvesControllerProvider
{
    public const ROUTE_PRODUCT_QUICK_ADD = 'product-quick-add';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $this->addCartQuickAddRoute();
    }

    /**
     * @return $this
     */
    protected function addCartQuickAddRoute()
    {
        $this->createPostController('/{productQuickAdd}', static::ROUTE_PRODUCT_QUICK_ADD, 'ProductQuickAddWidget', 'ProductQuickAddWidget')
            ->assert('productQuickAdd', $this->getAllowedLocalesPattern() . 'product-quick-add|product-quick-add')
            ->value('productQuickAdd', 'product-quick-add');

        return $this;
    }
}
