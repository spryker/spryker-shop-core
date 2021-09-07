<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class QuoteApprovalWidgetRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteApprovalWidget\Plugin\Router\QuoteApprovalWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_APPROVAL_APPROVE} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_APPROVAL_APPROVE = 'quote-approval-approve';
    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_APPROVAL_APPROVE = 'quote-approval-approve';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteApprovalWidget\Plugin\Router\QuoteApprovalWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_APPROVAL_DECLINE} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_APPROVAL_DECLINE = 'quote-approval-decline';
    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_APPROVAL_DECLINE = 'quote-approval-decline';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteApprovalWidget\Plugin\Router\QuoteApprovalWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_APPROVAL_CREATE} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_APPROVAL_CREATE = 'quote-approval-create';
    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_APPROVAL_CREATE = 'quote-approval-create';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteApprovalWidget\Plugin\Router\QuoteApprovalWidgetRouteProviderPlugin::ROUTE_NAME_QUOTE_APPROVAL_REMOVE} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_APPROVAL_REMOVE = 'quote-approval-remove';
    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_APPROVAL_REMOVE = 'quote-approval-remove';

    /**
     * @var string
     */
    protected const PATTERN_ID = '\d+';

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
        $routeCollection = $this->addQuoteApprovalApproveRoute($routeCollection);
        $routeCollection = $this->addQuoteApprovalDeclineRoute($routeCollection);
        $routeCollection = $this->addCreateQuoteApprovalRoute($routeCollection);
        $routeCollection = $this->addRemoveQuoteApprovalRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::approveAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteApprovalApproveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-approval/approve/{idQuoteApproval}', 'QuoteApprovalWidget', 'QuoteApproval', 'approveAction');
        $route = $route->setRequirement('idQuoteApproval', static::PATTERN_ID);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_APPROVAL_APPROVE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::declineAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteApprovalDeclineRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-approval/decline/{idQuoteApproval}', 'QuoteApprovalWidget', 'QuoteApproval', 'declineAction');
        $route = $route->setRequirement('idQuoteApproval', static::PATTERN_ID);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_APPROVAL_DECLINE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::createQuoteApprovalAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCreateQuoteApprovalRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-approval/create', 'QuoteApprovalWidget', 'QuoteApproval', 'createQuoteApprovalAction');
        $routeCollection->add(static::ROUTE_NAME_QUOTE_APPROVAL_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteApprovalWidget\Controller\QuoteApprovalController::removeQuoteApprovalAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addRemoveQuoteApprovalRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-approval/{idQuoteApproval}/remove', 'QuoteApprovalWidget', 'QuoteApproval', 'removeQuoteApprovalAction');
        $route = $route->setRequirement('idQuoteApproval', static::PATTERN_ID);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_APPROVAL_REMOVE, $route);

        return $routeCollection;
    }
}
