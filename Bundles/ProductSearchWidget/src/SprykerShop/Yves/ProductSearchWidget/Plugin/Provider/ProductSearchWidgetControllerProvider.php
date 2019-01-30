<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class ProductSearchWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_PRODUCT_CONCRETE_SEARCH = 'product-search/product-concrete-search';
    protected const ROUTE_PRODUCT_QUICK_ADD = 'product-quick-add';

    /**
     * {@inheritdoc}
     *
     * @param bool|null $sslEnabled
     */
    public function __construct(?bool $sslEnabled = null)
    {
        parent::__construct($sslEnabled);

        $this->allowedLocalesPattern = $this->getAllowedLocalesPattern();
    }

    /**
     * @uses ProductConcreteSearchController::indexAction()
     *
     * @param \Silex\Application $app
     *
     * @return void
     */
    protected function defineControllers(Application $app): void
    {
        $this->addCartQuickAddRoute();
        $this->addProductConcreteSearchRoute();
    }

    /**
     * @uses ProductConcreteSearchController::addAction()
     *
     * @return $this
     */
    protected function addCartQuickAddRoute()
    {
        $this->createPostController('/{productSearch}', static::ROUTE_PRODUCT_QUICK_ADD, 'ProductSearchWidget', 'ProductConcreteSearch', 'add')
            ->assert('productSearch', $this->getAllowedLocalesPattern() . 'product-quick-add|product-quick-add')
            ->value('productSearch', 'product-quick-add');

        return $this;
    }

    /**
     * @uses ProductConcreteSearchController::indexAction()
     *
     * @return $this
     */
    protected function addProductConcreteSearchRoute()
    {
        $this->createController('{productSearch}/product-concrete-search', static::ROUTE_PRODUCT_CONCRETE_SEARCH, 'ProductSearchWidget', 'ProductConcreteSearch')
            ->assert('productSearch', $this->allowedLocalesPattern . 'product-search|product-search')
            ->value('productSearch', 'product-search');
    }
}
