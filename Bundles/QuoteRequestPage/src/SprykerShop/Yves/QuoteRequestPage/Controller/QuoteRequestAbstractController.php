<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestPage\Controller;

use Generated\Shared\Transfer\QuoteRequestFilterTransfer;
use Generated\Shared\Transfer\QuoteRequestResponseTransfer;
use Generated\Shared\Transfer\QuoteRequestTransfer;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\QuoteRequestPage\QuoteRequestPageFactory getFactory()
 */
class QuoteRequestAbstractController extends AbstractController
{
    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST = 'quote-request';

    /**
     * @uses \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::PARAM_QUOTE_REQUEST_REFERENCE
     * @var string
     */
    protected const PARAM_QUOTE_REQUEST_REFERENCE = 'quoteRequestReference';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT = 'quote-request/edit';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_DETAILS} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_DETAILS = 'quote-request/details';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_SEND_TO_USER} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_SEND_TO_USER = 'quote-request/send-to-user';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_ITEMS_CONFIRM = 'quote-request/edit-items-confirm';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_ITEMS} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_ITEMS = 'quote-request/edit-items';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_ADDRESS} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_ADDRESS = 'quote-request/edit-address';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_ADDRESS_CONFIRM} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_ADDRESS_CONFIRM = 'quote-request/edit-address-confirm';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_SHIPMENT} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_SHIPMENT = 'quote-request/edit-shipment';

    /**
     * @deprecated Use {@link \SprykerShop\Yves\QuoteRequestPage\Plugin\Router\QuoteRequestPageRouteProviderPlugin::ROUTE_NAME_QUOTE_REQUEST_EDIT_SHIPMENT_CONFIRM} instead.
     * @var string
     */
    protected const ROUTE_QUOTE_REQUEST_EDIT_SHIPMENT_CONFIRM = 'quote-request/edit-shipment-confirm';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_NAME_CART
     * @var string
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_ADDRESS
     * @var string
     */
    protected const ROUTE_CHECKOUT_ADDRESS = 'checkout-address';

    /**
     * @uses \SprykerShop\Yves\CheckoutPage\Plugin\Router\CheckoutPageRouteProviderPlugin::ROUTE_NAME_CHECKOUT_SHIPMENT
     * @var string
     */
    protected const ROUTE_CHECKOUT_SHIPMENT = 'checkout-shipment';

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();

        $companyUserTransfer = $this->getFactory()
            ->getCompanyUserClient()
            ->findCompanyUser();

        if ($companyUserTransfer === null) {
            throw new NotFoundHttpException('Only company users are allowed to access this page');
        }
    }

    /**
     * @param string $quoteRequestReference
     * @param bool $withVersions
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteRequestTransfer
     */
    protected function getCompanyUserQuoteRequestByReference(
        string $quoteRequestReference,
        bool $withVersions = false
    ): QuoteRequestTransfer {
        $quoteRequestFilterTransfer = (new QuoteRequestFilterTransfer())
            ->setQuoteRequestReference($quoteRequestReference)
            ->setWithVersions($withVersions)
            ->setIdCompanyUser($this->getFactory()->getCompanyUserClient()->findCompanyUser()->getIdCompanyUser());

        $quoteRequestResponseTransfer = $this->getFactory()
            ->getQuoteRequestClient()
            ->getQuoteRequest($quoteRequestFilterTransfer);

        if (!$quoteRequestResponseTransfer->getIsSuccessful()) {
            throw new NotFoundHttpException();
        }

        return $quoteRequestResponseTransfer->getQuoteRequest();
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteRequestResponseTransfer $quoteRequestResponseTransfer
     *
     * @return void
     */
    protected function handleResponseErrors(QuoteRequestResponseTransfer $quoteRequestResponseTransfer): void
    {
        foreach ($quoteRequestResponseTransfer->getMessages() as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }
}
