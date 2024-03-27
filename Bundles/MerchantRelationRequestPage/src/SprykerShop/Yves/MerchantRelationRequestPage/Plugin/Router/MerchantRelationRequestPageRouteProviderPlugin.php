<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class MerchantRelationRequestPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_MERCHANT_RELATION_REQUEST = 'company/merchant-relation-request';

    /**
     * @var string
     */
    public const ROUTE_NAME_MERCHANT_RELATION_REQUEST_CREATE = 'company/merchant-relation-request/create';

    /**
     * @var string
     */
    public const ROUTE_NAME_MERCHANT_RELATION_REQUEST_DETAILS = 'company/merchant-relation-request/details';

    /**
     * @var string
     */
    public const ROUTE_NAME_MERCHANT_RELATION_REQUEST_CANCEL = 'company/merchant-relation-request/cancel';

    /**
     * @var string
     */
    protected const UUID_PATTERN = '[a-zA-Z0-9-_\.]+';

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
        $routeCollection = $this->addMerchantRelationRequestRoute($routeCollection);
        $routeCollection = $this->addMerchantRelationRequestCreateRoute($routeCollection);
        $routeCollection = $this->addMerchantRelationRequestDetailsRoute($routeCollection);
        $routeCollection = $this->addMerchantRelationRequestCancelRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\MerchantRelationRequestPage\Controller\MerchantRelationRequestViewController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMerchantRelationRequestRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/merchant-relation-request', 'MerchantRelationRequestPage', 'MerchantRelationRequestView', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_MERCHANT_RELATION_REQUEST, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\MerchantRelationRequestPage\Controller\MerchantRelationRequestCreateController::createAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMerchantRelationRequestCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/merchant-relation-request/create', 'MerchantRelationRequestPage', 'MerchantRelationRequestCreate', 'createAction');
        $routeCollection->add(static::ROUTE_NAME_MERCHANT_RELATION_REQUEST_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\MerchantRelationRequestPage\Controller\MerchantRelationRequestViewController::detailsAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMerchantRelationRequestDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/merchant-relation-request/details/{uuid}', 'MerchantRelationRequestPage', 'MerchantRelationRequestView', 'detailsAction');
        $route = $route->setRequirement('uuid', static::UUID_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_MERCHANT_RELATION_REQUEST_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\MerchantRelationRequestPage\Controller\MerchantRelationRequestDeleteController::cancelAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMerchantRelationRequestCancelRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/company/merchant-relation-request/cancel/{uuid}', 'MerchantRelationRequestPage', 'MerchantRelationRequestCancel', 'cancelAction');
        $route = $route->setRequirement('uuid', static::UUID_PATTERN);
        $routeCollection->add(static::ROUTE_NAME_MERCHANT_RELATION_REQUEST_CANCEL, $route);

        return $routeCollection;
    }
}
