<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Controller;

use Generated\Shared\Transfer\ExpressCheckoutPaymentRequestTransfer;
use Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer;
use Generated\Shared\Transfer\PaymentCustomerRequestTransfer;
use Generated\Shared\Transfer\PaymentCustomerResponseTransfer;
use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory getFactory()
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig getConfig()
 */
class ExpressCheckoutSuccessController extends ExpressCheckoutAbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function successAction(Request $request): Response
    {
        $csrfToken = $this->getCsrfToken($request);

        if (!$this->getFactory()->getCsrfTokenManager()->isTokenValid($csrfToken)) {
            return $this->createErrorJsonResponse(static::GLOSSARY_KEY_FORM_CSRF_VALIDATION_ERROR);
        }

        $quoteClient = $this->getFactory()->getQuoteClient();
        $quoteTransfer = $quoteClient->getQuote();

        $paymentTransfer = $quoteTransfer->getPayments()->offsetGet(0);

        if (!$paymentTransfer) {
            return $this->createErrorJsonResponse(static::GLOSSARY_KEY_INCORRECT_QUOTE);
        }

        $paymentCustomerResponseTransfer = $this->getExpressCheckoutCustomerByPayment($paymentTransfer, $quoteTransfer);

        if ($paymentCustomerResponseTransfer->getIsSuccessful() === false) {
            $this->getLogger()->error('Failed to get customer data from payment provider', ['response' => $paymentCustomerResponseTransfer->toArray()]);

            return $this->createErrorJsonResponse(static::GLOSSARY_KEY_PAYMENT_FAILED);
        }

        $this->getFactory()->createQuoteCustomerExpander()
            ->expandQuoteWithCustomerData($quoteTransfer, $paymentCustomerResponseTransfer->getCustomerOrFail());

        $expressCheckoutPaymentResponseTransfer = $this->processExpressCheckoutPaymentRequest($quoteTransfer);

        if ($expressCheckoutPaymentResponseTransfer->getErrors()->count()) {
            foreach ($expressCheckoutPaymentResponseTransfer->getErrors() as $errorTransfer) {
                $this->addErrorMessage($errorTransfer->getMessageOrFail());
            }

            return $this->createErrorJsonResponse();
        }

        $quoteClient->setQuote($expressCheckoutPaymentResponseTransfer->getQuoteOrFail());

        return $this->createSuccessJsonResponse(
            $this->getRouter()->generate($this->getFactory()->getConfig()->getSuccessActionRedirectRouteName()),
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\PaymentCustomerResponseTransfer
     */
    protected function getExpressCheckoutCustomerByPayment(
        PaymentTransfer $paymentTransfer,
        QuoteTransfer $quoteTransfer
    ): PaymentCustomerResponseTransfer {
        $paymentCustomerRequestTransfer = (new PaymentCustomerRequestTransfer())
            ->setPayment($paymentTransfer)
            ->setCustomerPaymentServiceProviderData($quoteTransfer->getPreOrderPaymentData());

        return $this->getFactory()->getPaymentAppClient()->getCustomer($paymentCustomerRequestTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\ExpressCheckoutPaymentResponseTransfer
     */
    protected function processExpressCheckoutPaymentRequest(
        QuoteTransfer $quoteTransfer
    ): ExpressCheckoutPaymentResponseTransfer {
        $expressCheckoutPaymentRequestTransfer = (new ExpressCheckoutPaymentRequestTransfer())
            ->setQuote($quoteTransfer);

        return $this->getFactory()->getPaymentAppClient()->processExpressCheckoutPaymentRequest($expressCheckoutPaymentRequestTransfer);
    }
}
