<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductSearchWidget\Plugin\Provider;

use Silex\Application;
use SprykerShop\Yves\ProductSearchWidget\Controller\ProductConcreteSearchController;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class ProductSearchWidgetControllerProvider extends AbstractYvesControllerProvider
{
    protected const ROUTE_PRODUCT_CONCRETE_SEARCH = 'product-search/concrete';

    /**
     * @var string
     */
    protected $allowedLocalesPattern;

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
        $this->createController('{productSearch}/suggest-product-concrete', static::ROUTE_PRODUCT_CONCRETE_SEARCH, 'ProductSearchWidget', 'ProductConcreteSearch', 'index')
            ->assert('productSearch', $this->allowedLocalesPattern . 'product-search|product-search')
            ->value('productSearch', 'product-search');
    }
}
