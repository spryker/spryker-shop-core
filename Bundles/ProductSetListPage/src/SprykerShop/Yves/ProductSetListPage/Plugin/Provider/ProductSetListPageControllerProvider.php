<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ProductSetListPage\Plugin\Provider;

use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;
use Silex\Application;

class ProductSetListPageControllerProvider extends AbstractYvesControllerProvider
{

    const ROUTE_PRODUCT_SETS = 'product-sets';

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app)
    {
        $allowedLocalesPattern = $this->getAllowedLocalesPattern();

        $this->createController('/{sets}', self::ROUTE_PRODUCT_SETS, 'ProductSetListPage', 'List', 'index')
            ->assert('sets', $allowedLocalesPattern . 'product-sets|product-sets')
            ->value('sets', 'product-sets');
    }

}
