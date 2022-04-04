<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;
use Symfony\Component\HttpFoundation\Request;

class MerchantProductOfferWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @var string
     */
    public const ROUTE_NAME_MERCHANT_PRODUCT_OFFERS_SELECT = 'merchant-product-offer-widget/merchant-product-offers-select';

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
        $routeCollection = $this->addMerchantProductOffersSelectRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\CommentWidget\Controller\CommentController::addAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addMerchantProductOffersSelectRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/merchant-product-offer-widget/merchant-product-offers-select', 'MerchantProductOfferWidget', 'MerchantProductOffersSelect');
        $route = $route->setMethods(Request::METHOD_GET);
        $routeCollection->add(static::ROUTE_NAME_MERCHANT_PRODUCT_OFFERS_SELECT, $route);

        return $routeCollection;
    }
}
