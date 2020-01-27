<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Controller;

use ArrayObject;
use Generated\Shared\Transfer\QuoteValidationResponseTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\CheckoutPage\Plugin\Provider\CheckoutPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageFactory getFactory()
 * @method \Spryker\Client\Checkout\CheckoutClientInterface getClient()
 */
class CheckoutController extends AbstractController
{
    use PermissionAwareTrait;

    public const MESSAGE_PERMISSION_FAILED = 'global.permission.failed';
    protected const MESSAGE_SHIPMENT_SUCCESS_SAVE = 'global.shipment.success.save';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function indexAction(Request $request)
    {
        $quoteValidationResponseTransfer = $this->canProceedCheckout();

        if (!$quoteValidationResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($quoteValidationResponseTransfer->getMessages());

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $response = $this->createStepProcess()->process($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function customerAction(Request $request)
    {
        $quoteValidationResponseTransfer = $this->canProceedCheckout();

        if (!$quoteValidationResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($quoteValidationResponseTransfer->getMessages());

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $response = $this->createStepProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->createCustomerFormCollection()
        );

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            $this->getFactory()->getCustomerPageWidgetPlugins(),
            '@CheckoutPage/views/login/login.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function addressAction(Request $request)
    {
        $quoteValidationResponseTransfer = $this->canProceedCheckout();

        if (!$quoteValidationResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($quoteValidationResponseTransfer->getMessages());

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $response = $this->createStepProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->createAddressFormCollection()
        );

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            $this->getFactory()->getCustomerPageWidgetPlugins(),
            '@CheckoutPage/views/address/address.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function shipmentAction(Request $request)
    {
        $quoteValidationResponseTransfer = $this->canProceedCheckout();

        if (!$quoteValidationResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($quoteValidationResponseTransfer->getMessages());

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $response = $this->createStepProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->createShipmentFormCollection()
        );

        if (!is_array($response)) {
            $response = $this->executeCheckoutShipmentPostExecutePlugins($response);
            $this->showSuccessMassageIfExists($response);

            return $response;
        }

        return $this->view(
            $response,
            $this->getFactory()->getCustomerPageWidgetPlugins(),
            '@CheckoutPage/views/shipment/shipment.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function paymentAction(Request $request)
    {
        $quoteValidationResponseTransfer = $this->canProceedCheckout();

        if (!$quoteValidationResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($quoteValidationResponseTransfer->getMessages());

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $response = $this->createStepProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->getPaymentFormCollection()
        );

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            $this->getFactory()->getCustomerPageWidgetPlugins(),
            '@CheckoutPage/views/payment/payment.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function summaryAction(Request $request)
    {
        $quoteValidationResponseTransfer = $this->canProceedCheckout();

        if (!$quoteValidationResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($quoteValidationResponseTransfer->getMessages());

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $viewData = $this->createStepProcess()->process(
            $request,
            $this->getFactory()
                ->createCheckoutFormFactory()
                ->createSummaryFormCollection()
        );

        if (!is_array($viewData)) {
            return $viewData;
        }

        return $this->view(
            $viewData,
            $this->getFactory()->getSummaryPageWidgetPlugins(),
            '@CheckoutPage/views/summary/summary.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function placeOrderAction(Request $request)
    {
        $quoteValidationResponseTransfer = $this->canProceedCheckout();

        if (!$quoteValidationResponseTransfer->getIsSuccessful()) {
            $this->processErrorMessages($quoteValidationResponseTransfer->getMessages());

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        $grandTotal = $quoteTransfer
            ->getTotals()
            ->getGrandTotal();

        if (!$this->can('PlaceOrderWithAmountUpToPermissionPlugin', $grandTotal)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CheckoutPageControllerProvider::CHECKOUT_SUMMARY);
        }

        if ($quoteTransfer->getOrderReference() !== null) {
            return $this->redirectResponseInternal(CheckoutPageControllerProvider::CHECKOUT_SUCCESS);
        }

        return $this->createStepProcess()->process($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function successAction(Request $request)
    {
        $response = $this->createStepProcess()->process($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view(
            $response,
            $this->getFactory()->getCustomerPageWidgetPlugins(),
            '@CheckoutPage/views/order-success/order-success.twig'
        );
    }

    /**
     * @return mixed
     */
    public function errorAction()
    {
        return $this->view([], [], '@CheckoutPage/views/order-fail/order-fail.twig');
    }

    /**
     * @return \Generated\Shared\Transfer\QuoteValidationResponseTransfer
     */
    protected function canProceedCheckout(): QuoteValidationResponseTransfer
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        return $this->getFactory()
            ->getCheckoutClient()
            ->isQuoteApplicableForCheckout($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[]|\ArrayObject $messageTransfers
     *
     * @return void
     */
    protected function processErrorMessages(ArrayObject $messageTransfers): void
    {
        foreach ($messageTransfers as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }

    /**
     * @return \Spryker\Yves\StepEngine\Process\StepEngineInterface
     */
    protected function createStepProcess()
    {
        return $this->getFactory()->createCheckoutProcess();
    }

    /**
     * @param \Symfony\Component\HttpFoundation\RedirectResponse $response
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCheckoutShipmentPostExecutePlugins(RedirectResponse $response): RedirectResponse
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        $checkoutShipmentPostExecutePlugins = $this->getFactory()->getCheckoutShipmentPostExecuteStrategyPlugins();

        foreach ($checkoutShipmentPostExecutePlugins as $checkoutShipmentPostExecuteStrategyPlugin) {
            if ($checkoutShipmentPostExecuteStrategyPlugin->isApplicable($quoteTransfer)) {
                $response = $checkoutShipmentPostExecuteStrategyPlugin->execute($this->getRouter());
            }
        }

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\RedirectResponse $response
     *
     * @return void
     */
    protected function showSuccessMassageIfExists(RedirectResponse $response): void
    {
        if ($response->headers->has(self::MESSAGE_SHIPMENT_SUCCESS_SAVE)) {
            $this->addSuccessMessage($response->headers->get(self::MESSAGE_SHIPMENT_SUCCESS_SAVE));
        }
    }
}
