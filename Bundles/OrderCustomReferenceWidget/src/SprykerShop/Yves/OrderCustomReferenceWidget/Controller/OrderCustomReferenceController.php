<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Controller;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\OrderCustomReferenceWidget\OrderCustomReferenceWidgetFactory getFactory()
 */
class OrderCustomReferenceController extends AbstractController
{
    protected const PARAMETER_BACK_URL = 'backUrl';
    protected const PARAMETER_ORDER_CUSTOM_REFERENCE = 'orderCustomReference';
    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_SAVED = 'order_custom_reference.reference_saved';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function saveAction(Request $request): RedirectResponse
    {
        $redirectResponse = $this->executeSaveAction($request);

        return $redirectResponse;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeSaveAction(Request $request): RedirectResponse
    {
        $backUrl = $request->request->get(static::PARAMETER_BACK_URL);
        $orderCustomReference = $request->request->get(static::PARAMETER_ORDER_CUSTOM_REFERENCE);

        $quoteResponseTransfer = $this->getFactory()
            ->createOrderCustomReferenceSetter()
            ->setOrderCustomReference($orderCustomReference);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_SAVED);
        }

        $this->handleQuoteResponseTransferErrors($quoteResponseTransfer);

        return $this->redirectResponseExternal($backUrl);
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteResponseTransfer $quoteResponseTransfer
     *
     * @return void
     */
    protected function handleQuoteResponseTransferErrors(QuoteResponseTransfer $quoteResponseTransfer): void
    {
        foreach ($quoteResponseTransfer->getErrors() as $quoteErrorTransfer) {
            $this->addErrorMessage($quoteErrorTransfer->getMessage());
        }
    }
}
