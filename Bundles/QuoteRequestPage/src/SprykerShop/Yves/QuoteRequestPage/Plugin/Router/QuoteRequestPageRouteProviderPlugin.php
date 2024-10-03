<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Plugin\Router;

use Spryker\Yves\Router\Plugin\RouteProvider\AbstractRouteProviderPlugin;
use Spryker\Yves\Router\Route\RouteCollection;

class QuoteRequestPageRouteProviderPlugin extends AbstractRouteProviderPlugin
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST = 'quote-request';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST = 'quote-request';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_CREATE} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_CREATE = 'quote-request/create';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_CREATE = 'quote-request/create';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_REVISE} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_REVISE = 'quote-request/revise';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_REVISE = 'quote-request/revise';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_SEND_TO_USER} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_SEND_TO_USER = 'quote-request/send-to-user';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_SEND_TO_USER = 'quote-request/send-to-user';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT = 'quote-request/edit';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_EDIT = 'quote-request/edit';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_ITEMS} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_ITEMS = 'quote-request/edit-items';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_EDIT_ITEMS = 'quote-request/edit-items';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM = 'quote-request/edit-items-confirm';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM = 'quote-request/edit-items-confirm';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_CANCEL} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_CANCEL = 'quote-request/cancel';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_CANCEL = 'quote-request/cancel';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_DETAILS} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_DETAILS = 'quote-request/details';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_DETAILS = 'quote-request/details';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_CONVERT_TO_CART} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_CONVERT_TO_CART = 'quote-request/convert-to-cart';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_CONVERT_TO_CART = 'quote-request/convert-to-cart';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST} instead.
     *
     * @var string
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST} instead.
     *
     * @var string
     */
    protected const QUOTE_REQUEST_REFERENCE_REGEX = '[a-zA-Z0-9-_]+';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_ADDRESS} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_ADDRESS = 'quote-request/edit-address';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_EDIT_ADDRESS = 'quote-request/edit-address';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_ADDRESS_CONFIRM} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_ADDRESS_CONFIRM = 'quote-request/edit-address-confirm';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_EDIT_ADDRESS_CONFIRM = 'quote-request/edit-address-confirm';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_SHIPMENT} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_SHIPMENT = 'quote-request/edit-shipment';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_EDIT_SHIPMENT = 'quote-request/edit-shipment';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_SHIPMENT_CONFIRM} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_SHIPMENT_CONFIRM = 'quote-request/edit-shipment-confirm';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_EDIT_SHIPMENT_CONFIRM = 'quote-request/edit-shipment-confirm';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_SAVE} instead.
     *
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_SAVE = 'quote-request/save';

    /**
     * @var string
     */
    public const ROUTE_NAME_QUOTE_REQUEST_SAVE = 'quote-request/save';

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
        $routeCollection = $this->addQuoteRequestRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestCreateRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestReviseRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditItemsRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditItemsConfirmRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestSendToUserRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestCancelRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestDetailsRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestConvertToCartRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditAddressRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditAddressConfirmRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditShipmentRoute($routeCollection);
        $routeCollection = $this->addQuoteRequestEditShipmentConfirmRoute($routeCollection);
        $routeCollection = $this->addCheckoutSaveRoute($routeCollection);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestViewController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request', 'QuoteRequestPage', 'QuoteRequestView', 'indexAction');
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestCreateController::createAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestCreateRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/create', 'QuoteRequestPage', 'QuoteRequestCreate', 'createAction');
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_CREATE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestReviseController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestReviseRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/revise/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestRevise', 'indexAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_REVISE, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/edit/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEdit', 'indexAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_EDIT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditItemsController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditItemsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/edit-items/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEditItems', 'indexAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_EDIT_ITEMS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditItemsController::confirmAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditItemsConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/edit-items-confirm/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEditItems', 'confirmAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditController::sendToUserAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestSendToUserRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/send-to-user/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEdit', 'sendToUserAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_SEND_TO_USER, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestDeleteController::cancelAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestCancelRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/cancel/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestDelete', 'cancelAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_CANCEL, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestViewController::detailsAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestDetailsRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/details/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestView', 'detailsAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_DETAILS, $route);

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
        $route = $this->buildRoute('/quote-request/convert-to-cart/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestCheckout', 'convertToCartAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_CONVERT_TO_CART, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditAddressController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditAddressRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/edit-address/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEditAddress', 'indexAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_EDIT_ADDRESS, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditAddressController::confirmAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditAddressConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/edit-address-confirm/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEditAddress', 'confirmAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_EDIT_ADDRESS_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditShipmentController::indexAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditShipmentRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/edit-shipment/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEditShipment', 'indexAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_EDIT_SHIPMENT, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestEditShipmentController::confirmAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addQuoteRequestEditShipmentConfirmRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/edit-shipment-confirm/{quoteRequestReference}', 'QuoteRequestPage', 'QuoteRequestEditShipment', 'confirmAction');
        $route = $route->setRequirement(static::PARAM_QUOTE_REQUEST_REFERENCE, static::QUOTE_REQUEST_REFERENCE_REGEX);
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_EDIT_SHIPMENT_CONFIRM, $route);

        return $routeCollection;
    }

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Controller\QuoteRequestSaveController::saveAction()
     *
     * @param \Spryker\Yves\Router\Route\RouteCollection $routeCollection
     *
     * @return \Spryker\Yves\Router\Route\RouteCollection
     */
    protected function addCheckoutSaveRoute(RouteCollection $routeCollection): RouteCollection
    {
        $route = $this->buildRoute('/quote-request/save', 'QuoteRequestPage', 'QuoteRequestSave', 'saveAction');
        $routeCollection->add(static::ROUTE_NAME_QUOTE_REQUEST_SAVE, $route);

        return $routeCollection;
    }
}
