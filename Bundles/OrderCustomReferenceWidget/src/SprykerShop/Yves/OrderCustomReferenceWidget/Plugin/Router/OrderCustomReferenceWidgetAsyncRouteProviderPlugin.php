<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class OrderCustomReferenceWidgetAsyncRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_ORDER_CUSTOM_REFERENCE_ASYNC_SAVE = 'order-custom-reference/async/save';

    /**
     * {@inheritDoc}
     * - Adds order custom reference save action to RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addOrderCustomReferenceAsyncSaveRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\OrderCustomReferenceWidget\Controller\OrderCustomReferenceAsyncController::saveAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addOrderCustomReferenceAsyncSaveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/order-custom-reference/async/save',
            'OrderCustomReferenceWidget',
            'OrderCustomReferenceAsync',
            'saveAction',
        );
        $routeCollection->add(static::ROUTE_NAME_ORDER_CUSTOM_REFERENCE_ASYNC_SAVE, $route);

        return $routeCollection;
    }
}
