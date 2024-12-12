<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Controller;

use Generated\Shared\Transfer\PaymentTransfer;
use Generated\Shared\Transfer\PreOrderPaymentRequestTransfer;
use Generated\Shared\Transfer\PreOrderPaymentResponseTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory getFactory()
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig getConfig()
 */
class ExpressCheckoutCancelController extends ExpressCheckoutAbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function cancelAction(Request $request): Response
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

        $preOrderPaymentResponseTransfer = $this->cancelPreOrderPayment($quoteTransfer, $paymentTransfer);
        if (!$preOrderPaymentResponseTransfer->getIsSuccessful()) {
            $this->getLogger()->error('Express checkout cancellation failed.', ['response' => $preOrderPaymentResponseTransfer->toArray()]);

            return $this->createErrorJsonResponse(static::GLOSSARY_KEY_PAYMENT_FAILED);
        }

        $quoteTransfer = $this->cleanQuotePayments($quoteTransfer);
        $quoteClient->setQuote($quoteTransfer);

        $cancelActionRedirectRouteName = $this->getFactory()->getConfig()->getCancelActionRedirectRouteName();

        return $this->createSuccessJsonResponse($this->getRedirectUrl($cancelActionRedirectRouteName));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param \Generated\Shared\Transfer\PaymentTransfer $paymentTransfer
     *
     * @return \Generated\Shared\Transfer\PreOrderPaymentResponseTransfer
     */
    protected function cancelPreOrderPayment(
        QuoteTransfer $quoteTransfer,
        PaymentTransfer $paymentTransfer
    ): PreOrderPaymentResponseTransfer {
        $preOrderPaymentRequestTransfer = (new PreOrderPaymentRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setPayment($paymentTransfer)
            ->setPreOrderPaymentData($quoteTransfer->getPreOrderPaymentData());

        return $this->getFactory()->getPaymentAppClient()->cancelPreOrderPayment($preOrderPaymentRequestTransfer);
    }
}
