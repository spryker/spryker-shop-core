<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentAppWidget\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetFactory getFactory()
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig getConfig()
 */
class ExpressCheckoutFailureController extends ExpressCheckoutAbstractController
{
 /**
  * @param \Symfony\Component\HttpFoundation\Request $request
  *
  * @return \Symfony\Component\HttpFoundation\Response
  */
    public function failureAction(Request $request): Response
    {
        $this->getLogger()->error('Payment failed', ['request' => $request->request->all()]);
        $this->addErrorMessage(static::GLOSSARY_KEY_PAYMENT_FAILED);

        $quoteClient = $this->getFactory()->getQuoteClient();
        $quoteTransfer = $quoteClient->getQuote();

        $quoteTransfer = $this->cleanQuotePayments($quoteTransfer);
        $quoteClient->setQuote($quoteTransfer);

        $failureActionRedirectRouteName = $this->getFactory()->getConfig()->getFailureActionRedirectRouteName();

        return $this->createSuccessJsonResponse($this->getRedirectUrl($failureActionRedirectRouteName));
    }
}
