<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget\Controller;

use Generated\Shared\Transfer\CartReorderResponseTransfer;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetConfig getConfig()
 * @method \SprykerShop\Yves\SalesOrderAmendmentWidget\SalesOrderAmendmentWidgetFactory getFactory()
 */
class OrderAmendmentController extends AbstractOrderAmendmentController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $orderReference
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function amendOrderAction(Request $request, string $orderReference): RedirectResponse
    {
        $orderAmendmentForm = $this->getFactory()
            ->getOrderAmendmentForm()
            ->handleRequest($request);

        if (!$orderAmendmentForm->isSubmitted() || !$orderAmendmentForm->isValid()) {
            $this->addErrorMessagesFromForm($orderAmendmentForm);

            return $this->redirectToFailureUrl();
        }

        $cartReorderResponseTransfer = $this->getFactory()
            ->createOrderAmendmentHandler()
            ->amendOrder($orderReference, $request);

        $this->handleCartReorderResponseErrors($cartReorderResponseTransfer);

        if ($cartReorderResponseTransfer->getErrors()->count()) {
            return $this->redirectToFailureUrl();
        }

        return $this->redirectToSuccessfulUrl();
    }

    /**
     * @param \Generated\Shared\Transfer\CartReorderResponseTransfer $cartReorderResponseTransfer
     *
     * @return void
     */
    protected function handleCartReorderResponseErrors(CartReorderResponseTransfer $cartReorderResponseTransfer): void
    {
        foreach ($cartReorderResponseTransfer->getErrors() as $errorTransfer) {
            $this->addErrorMessage($errorTransfer->getMessageOrFail());
        }
    }
}
