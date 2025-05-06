<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetConfig getConfig()
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetFactory getFactory()
 */
class CancelOrderAmendmentController extends AbstractOrderAmendmentController
{
    /**
     * @var string
     */
    protected const GLOSSARY_KEY_AMENDMENT_CANT_BE_CANCELED = 'sales_order_amendment_widget.amendment_cant_be_canceled';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_AMENDMENT_CANCELED = 'sales_order_amendment_widget.amendment_canceled';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $amendedOrderReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request, string $amendedOrderReference): RedirectResponse
    {
        $cancelOrderAmendmentForm = $this->getFactory()
            ->getCancelOrderAmendmentForm()
            ->handleRequest($request);

        if (!$cancelOrderAmendmentForm->isSubmitted() || !$cancelOrderAmendmentForm->isValid()) {
            $this->addErrorMessagesFromForm($cancelOrderAmendmentForm);

            return $this->redirectToFailureUrl();
        }

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        if ($quoteTransfer->getAmendmentOrderReference() !== $amendedOrderReference) {
            $this->addErrorMessage(static::GLOSSARY_KEY_AMENDMENT_CANT_BE_CANCELED);

            return $this->redirectToFailureUrl();
        }

        $this->getFactory()->getSalesOrderAmendmentClient()->cancelOrderAmendment();
        $this->addSuccessMessage(static::GLOSSARY_KEY_AMENDMENT_CANCELED);

        return $this->redirectToSuccessfulUrl();
    }
}
