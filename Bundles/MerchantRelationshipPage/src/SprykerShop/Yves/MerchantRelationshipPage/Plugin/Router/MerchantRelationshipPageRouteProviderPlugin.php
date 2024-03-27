<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class MerchantRelationshipPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_MERCHANT_RELATION = 'company/merchant-relation';

    /**
     * @var string
     */
    public const ROUTE_NAME_MERCHANT_RELATION_DETAILS = 'company/merchant-relation/details';

    /**
     * {@inheritDoc}
     * - Adds MerchantRelationshipPage Routes to the RouteCollection.
     *
     * @api
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    public function addRoutes(RouteCollection $routeCollection): RouteCollection
    {
        $routeCollection = $this->addCompanyMerchantRelationsRoute($routeCollection);
        $routeCollection = $this->addCompanyMerchantRelationDetailsRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\MerchantRelationshipPage\Controller\TableController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyMerchantRelationsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/merchant-relation', 'MerchantRelationshipPage', 'Table');
        $routeCollection->add(static::ROUTE_NAME_MERCHANT_RELATION, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\MerchantRelationshipPage\Controller\DetailController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCompanyMerchantRelationDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/merchant-relation/details', 'MerchantRelationshipPage', 'Detail');
        $routeCollection->add(static::ROUTE_NAME_MERCHANT_RELATION_DETAILS, $route);

        return $routeCollection;
    }
}
