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
    protected const ROUTE_PRODUCT_ADDITIONAL_DATA = 'product-additional-data';

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
        $this->addProductAdditionalDataRoute();
    }

    /**
     * @uses ProductConcreteAddController::indexAction()
     *
     * @return void
     */
    protected function addCartQuickAddRoute(): void
    {
        $this->createPostController('/{productSearch}/product-quick-add', static::ROUTE_PRODUCT_QUICK_ADD, 'ProductSearchWidget', 'ProductConcreteAdd')
            ->assert('productSearch', $this->allowedLocalesPattern . 'product-search|product-search')
            ->value('productSearch', 'product-search');
    }

    /**
     * @return void
     */
    protected function addProductAdditionalDataRoute(): void
    {
        $this->createController('{quickAddToCart}/product-additional-data', static::ROUTE_PRODUCT_ADDITIONAL_DATA, 'ProductSearchWidget', 'QuickAddToCart', 'productAdditionalData')
            ->assert('quickAddToCart', $this->allowedLocalesPattern . 'quick-add-to-cart|quick-add-to-cart')
            ->value('quickAddToCart', 'quick-add-to-cart');
    }

    /**
     * @uses ProductConcreteSearchController::indexAction()
     *
     * @return void
     */
    protected function addProductConcreteSearchRoute(): void
    {
        $this->createController('{productSearch}/product-concrete-search', static::ROUTE_PRODUCT_CONCRETE_SEARCH, 'ProductSearchWidget', 'ProductConcreteSearch')
            ->assert('productSearch', $this->allowedLocalesPattern . 'product-search|product-search')
            ->value('productSearch', 'product-search');
    }
}
