<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartSharePage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class PersistentCartSharePageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @uses \SprykerShop\Yves\PersistentCartSharePage\Controller\CartController::previewAction()
     */
    protected const ROUTE_CART_PREVIEW = 'cart/preview';

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
        $routeCollection = $this->addPersistentCartSharePagePreviewRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\PersistentCartSharePage\Controller\CartController::previewAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addPersistentCartSharePagePreviewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cart/preview/{resourceShareUuid}', 'PersistentCartSharePage', 'Cart', 'previewAction');
        $routeCollection->add(static::ROUTE_CART_PREVIEW, $route);

        return $routeCollection;
    }
}
