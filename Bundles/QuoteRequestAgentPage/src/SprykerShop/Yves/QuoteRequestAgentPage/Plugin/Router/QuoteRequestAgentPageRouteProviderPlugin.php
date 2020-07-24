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
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT = 'agent/quote-request';

    public const ROUTE_NAME_QUOTE_REQUEST_AGENT = 'agent/quote-request';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CANCEL} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CANCEL = 'agent/quote-request/cancel';

    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_CANCEL = 'agent/quote-request/cancel';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_DETAILS} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_DETAILS = 'agent/quote-request/details';

    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_DETAILS = 'agent/quote-request/details';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT = 'agent/quote-request/edit';

    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT = 'agent/quote-request/edit';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CREATE} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CREATE = 'agent/quote-request/create';

    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_CREATE = 'agent/quote-request/create';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER = 'agent/quote-request/send-to-customer';

    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER = 'agent/quote-request/send-to-customer';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT_ITEMS} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS = 'agent/quote-request/edit-items';

    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT_ITEMS = 'agent/quote-request/edit-items';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT_ITEMS_CONFIRM} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_EDIT_ITEMS_CONFIRM = 'agent/quote-request/edit-items-confirm';

    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT_ITEMS_CONFIRM = 'agent/quote-request/edit-items-confirm';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_REVISE} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_REVISE = 'agent/quote-request/revise';

    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_REVISE = 'agent/quote-request/revise';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CONVERT_TO_CART} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CONVERT_TO_CART = 'agent/quote-request/convert-to-cart';

    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_CONVERT_TO_CART = 'agent/quote-request/convert-to-cart';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::PARAM_QUOTE_REQUEST_REFERENCE
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Provider\QuoteRequestAgentPageControllerProvider::QUOTE_REQUEST_REFERENCE_REGEX
     */
    protected const QUOTE_REQUEST_REFERENCE_REGEX = '[a-zA-Z0-9-]+';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_ADDRESS} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_ADDRESS = 'agent/quote-request/checkout-address';
    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_ADDRESS = 'agent/quote-request/checkout-address';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_ADDRESS_CONFIRM} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_ADDRESS_CONFIRM = 'agent/quote-request/checkout-address-confirm';
    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_ADDRESS_CONFIRM = 'agent/quote-request/checkout-address-confirm';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT = 'agent/quote-request/checkout-shipment';
    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT = 'agent/quote-request/checkout-shipment';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT_CONFIRM} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT_CONFIRM = 'agent/quote-request/checkout-shipment-confirm';
    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT_CONFIRM = 'agent/quote-request/checkout-shipment-confirm';
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestAgentPage\Plugin\Router\QuoteRequestAgentPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_SAVE} instead.
     */
    protected const ROUTE_QUOTE_REQUEST_AGENT_CHECKOUT_SAVE = 'agent/quote-request/checkout-save';
    public const ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_SAVE = 'agent/quote-request/checkout-save';

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
        $routeCollection = $this->addQuoteRequestAgentCheckoutAddressRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestAgentCheckoutShipmentRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestAgentCheckoutAddressConfirmRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestAgentCheckoutShipmentConfirmRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestAgentCheckoutSaveRoute($routeCollection);

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
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT, $route);

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
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_CANCEL, $route);

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
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_DETAILS, $route);

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
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_REVISE, $route);

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
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT, $route);

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
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_CREATE, $route);

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
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_SEND_TO_CUSTOMER, $route);

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
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT_ITEMS, $route);

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
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_EDIT_ITEMS_CONFIRM, $route);

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
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_CONVERT_TO_CART, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentCheckoutAddressController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestAgentCheckoutAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/checkout-address/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentCheckoutAddress', 'indexAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditItemsController::confirmAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestAgentCheckoutAddressConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/checkout-address-confirm/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentCheckoutAddress', 'confirmAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_ADDRESS_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentCheckoutShipmentController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestAgentCheckoutShipmentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/checkout-shipment/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentCheckoutShipment', 'indexAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentEditItemsController::confirmAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestAgentCheckoutShipmentConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/checkout-shipment-confirm/{quoteRequestReference}', 'QuoteRequestAgentPage', 'QuoteRequestAgentCheckoutShipment', 'confirmAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_SHIPMENT_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestAgentPage\Controller\QuoteRequestAgentSaveController::saveAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestAgentCheckoutSaveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/agent/quote-request/checkout-save', 'QuoteRequestAgentPage', 'QuoteRequestAgentSave', 'saveAction');
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_AGENT_CHECKOUT_SAVE, $route);

        return $routeCollection;
    }
}
