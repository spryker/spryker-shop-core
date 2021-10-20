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
    /**
     * @var string
     */
    public const ROUTE_NAME_PRODUCT_CONFIGURATOR_GATEWAY_REQUEST = 'product-configurator-gateway/request';

    /**
     * @var string
     */
    public const ROUTE_NAME_PRODUCT_CONFIGURATOR_GATEWAY_RESPONSE = 'product-configurator-gateway/response';

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
        $routeCollection = $this->addProductConfiguratorGatewayResponse($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ProductConfiguratorGatewayPage\Controller\GatewayRequestController::indexAction().
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addProductConfiguratorGatewayRequest(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('/product-configurator-gateway/request', 'ProductConfiguratorGatewayPage', 'GatewayRequest', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_PRODUCT_CONFIGURATOR_GATEWAY_REQUEST, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ProductConfiguratorGatewayPage\Controller\GatewayResponseController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addProductConfiguratorGatewayResponse(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildPostRoute('/product-configurator-gateway/response', 'ProductConfiguratorGatewayPage', 'GatewayResponse', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_PRODUCT_CONFIGURATOR_GATEWAY_RESPONSE, $route);

        return $routeCollection;
    }
}
