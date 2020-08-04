<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class CmsPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\CmsPage\Plugin\Router\CmsPageRouteProviderPlugin::ROUTE_NAME_PREVIEW} instead.
     */
    protected const ROUTE_PREVIEW = 'cms-preview';
    public const ROUTE_NAME_PREVIEW = 'cms-preview';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\CmsPage\Plugin\Router\CmsPageRouteProviderPlugin::ID_CMS_PAGE} instead.
     */
    protected const PARAM_PAGE = 'page';
    public const ID_CMS_PAGE = 'page';

    protected const PAGE_NUMBER_PATTERN = '[0-9]+';

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
        $routeCollection = $this->addCmsPreviewRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCmsPreviewRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/cms/preview/{page}', 'CmsPage', 'Preview', 'indexAction');
        $route = $route->setRequirement(static::PARAM_PAGE, static::PAGE_NUMBER_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_PREVIEW, $route);

        return $routeCollection;
    }
}
