<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class QuoteRequestAgentPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    protected const ROUTE_QUOTE_REQUEST_AGENT = 'agent/quote-request';
    protected const ROUTE_QUOTE_REQUEST_AGENT_CANCEL = 'agent/quote-request/cancel';
    protected const ROUTE_QUOTE_REQUEST_AGENT_DETAILS = 'agent/quote-request/details';
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT = 'agent/quote-request/edit';
    protected const ROUTE_QUOTE_REQUEST_AGENT_CREATE = 'agent/quote-request/create';
    protected const ROUTE_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER = 'agent/quote-request/send-to-customer';
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS = 'agent/quote-request/edit-items';
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS_CONFIRM = 'agent/quote-request/edit-items-confirm';
    protected const ROUTE_QUOTE_REQUEST_AGENT_REVISE = 'agent/quote-request/revise';
    protected const ROUTE_QUOTE_REQUEST_AGENT_CONVERT_TO_CART = 'agent/quote-request/convert-to-cart';

    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    protected const QUOTE_REQUEST_REFERENCE_REGEX = '[a-zA-Z0-9-]+';

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
        $routeCollection = $this->addQuoteRequestAgentRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestAgentCancelRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestDetailsRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestReviseRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestSendToCustomerRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditItemsRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditItemsConfirmRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestCreateRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestConvertToCartRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentViewController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestAgentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request', 'QuoteRequestAgentPage', 'QuoteRequestAgentView', 'indexAction');
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentDeleteController::cancelAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestAgentCancelRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/cancel/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentDelete', 'cancelAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_CANCEL, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentViewController::detailsAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/details/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentView', 'detailsAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_DETAILS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditController::startEditAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestReviseRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/revise/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentRevise', 'indexAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_REVISE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditController::editAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/edit/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentEdit', 'editAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_EDIT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditController::editAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/create', 'QuoteRequestAgentPage', 'QuoteRequestAgentCreate', 'createAction');
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditController::sendToCustomerAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestSendToCustomerRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/send-to-customer/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentEdit', 'sendToCustomerAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditItemsController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditItemsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/edit-items/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentEditItems', 'indexAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditItemsController::confirmAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditItemsConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/edit-items-confirm/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentEditItems', 'confirmAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestCheckoutController::convertToCartAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestConvertToCartRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/convert-to-cart/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentCheckout', 'convertToCartAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_QUOTE_REQUEST_AGENT_CONVERT_TO_CART, $route);

        return $routeCollection;
    }
}
