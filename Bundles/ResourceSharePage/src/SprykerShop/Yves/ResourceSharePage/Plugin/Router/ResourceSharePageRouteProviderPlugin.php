<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ResourceSharePage\Plugin\Router;

use SprykerShop\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use SprykerShop\Yves\Router\Route\RouteCollection;

class ResourceSharePageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_RESOURCE_SHARE_LINK = 'resource-share/link';

    /**
     * Specification:
     * - Adds Routes to the RouteCollection.
     *
     * @api
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addLinkRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\ResourceSharePage\Controller\LinkController::indexAction()
     *
     * @param \SprykerShop\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \SprykerShop\Yves\Router\Route\RouteCollection
     */
    protected function addLinkRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/resource-share/link/{resourceShareUuid}', 'ResourceSharePage', 'Link', 'indexAction');
        $routeCollection->add(static::ROUTE_RESOURCE_SHARE_LINK, $route);

        return $routeCollection;
    }
}
