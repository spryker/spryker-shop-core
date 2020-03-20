<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class OrderCustomReferenceWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_ORDER_CUSTOM_REFERENCE_SAVE = 'order-custom-reference/save';

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
        $routeCollection = $this->addOrderCustomReferenceSaveRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\OrderCustomReferenceWidget\Controller\OrderCustomReferenceController::saveAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addOrderCustomReferenceSaveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute(
            '/order-custom-reference/save',
            'OrderCustomReferenceWidget',
            'OrderCustomReference',
            'saveAction'
        );
        $routeCollection->add(static::ROUTE_ORDER_CUSTOM_REFERENCE_SAVE, $route);

        return $routeCollection;
    }
}
