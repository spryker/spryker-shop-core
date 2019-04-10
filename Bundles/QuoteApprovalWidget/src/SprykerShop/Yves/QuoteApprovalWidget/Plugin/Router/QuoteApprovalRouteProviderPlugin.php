<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Plugin\Router;

use Silex\Application;
use SprykerShop\Yves\ShopApplication\Plugin\Provider\AbstractYvesControllerProvider;

class QuoteApprovalRouteProviderPlugin extends \Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin
{
    protected const ROUTE_QUOTE_APPROVAL_APPROVE = 'quote-approval-approve';
    protected const ROUTE_QUOTE_APPROVAL_DECLINE = 'quote-approval-decline';
    protected const ROUTE_QUOTE_APPROVAL_CREATE = 'quote-approval-create';
    protected const ROUTE_QUOTE_APPROVAL_REMOVE = 'quote-approval-remove';

    protected const PATTERN_ID = '\d+';

    /**
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    public function addRoutes(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $routeCollection = $this->addQuoteApprovalApproveRoute($routeCollection);
        $routeCollection = $this->addQuoteApprovalDeclineRoute($routeCollection);
        $routeCollection = $this->addCreateQuoteApprovalRoute($routeCollection);
        $routeCollection = $this->addRemoveQuoteApprovalRoute($routeCollection);
        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::approveAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addQuoteApprovalApproveRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/quote-approval/approve/{idQuoteApproval}', 'QuoteApprovalWidget', 'QuoteApproval', 'approveAction');
        $route = $route->assert('idQuoteApproval', static::PATTERN_ID);
        $routeCollection->add(static::ROUTE_QUOTE_APPROVAL_APPROVE, $route);
        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::declineAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addQuoteApprovalDeclineRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/quote-approval/decline/{idQuoteApproval}', 'QuoteApprovalWidget', 'QuoteApproval', 'declineAction');
        $route = $route->assert('idQuoteApproval', static::PATTERN_ID);
        $routeCollection->add(static::ROUTE_QUOTE_APPROVAL_DECLINE, $route);
        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::createQuoteApprovalAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addCreateQuoteApprovalRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/quote-approval/create', 'QuoteApprovalWidget', 'QuoteApproval', 'createQuoteApprovalAction');
        $routeCollection->add(static::ROUTE_QUOTE_APPROVAL_CREATE, $route);
        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::removeQuoteApprovalAction()
     *
     * @param \Spryker\Shared\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Shared\Router\Route\RouteCollection
     */
    protected function addRemoveQuoteApprovalRoute(\Spryker\Shared\Router\Route\RouteCollection $routeCollection): \Spryker\Shared\Router\Route\RouteCollection
    {
        $route = $this->buildRoute('/quote-approval/{idQuoteApproval}/remove', 'QuoteApprovalWidget', 'QuoteApproval', 'removeQuoteApprovalAction');
        $route = $route->assert('idQuoteApproval', static::PATTERN_ID);
        $routeCollection->add(static::ROUTE_QUOTE_APPROVAL_REMOVE, $route);
        return $routeCollection;
    }
}
