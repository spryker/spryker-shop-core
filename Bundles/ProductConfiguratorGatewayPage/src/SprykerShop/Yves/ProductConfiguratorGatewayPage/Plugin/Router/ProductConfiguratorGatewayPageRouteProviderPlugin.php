<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductConfiguratorGatewayPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class ProductConfiguratorGatewayPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const PRODUCT_CONFIGURATION_GATEWAY_REQUEST_ROUTE = 'product-configurator-gateway-request';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addProductConfiguratorGatewayRequest($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\SalesReturnPage\Controller\ReturnCreateController::createAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addProductConfiguratorGatewayRequest(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/product-configurator-gateway/request', 'ProductConfiguratorGatewayPage', 'GatewayRequest', 'indexAction');
        $routeCollection->add(static::PRODUCT_CONFIGURATION_GATEWAY_REQUEST_ROUTE, $route);

        return $routeCollection;
    }
}
