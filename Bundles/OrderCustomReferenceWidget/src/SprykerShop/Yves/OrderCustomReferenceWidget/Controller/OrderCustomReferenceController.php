<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Controller;

use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\OrderCustomReferenceWidget\Form\OrderCustomReferenceForm;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\OrderCustomReferenceWidget\OrderCustomReferenceWidgetFactory getFactory()
 */
class OrderCustomReferenceController extends AbstractController
{
    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_SAVED = 'order_custom_reference.reference_saved';

    protected const FORM_ORDER_CUSTOM_REFERENCE = 'order_custom_reference_form';

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
        $orderCustomReferenceForm = $this->getFactory()
            ->getOrderCustomReferenceForm()
            ->handleRequest($request);
        $quoteClient = $this->getFactory()->getQuoteClient();

        if ($orderCustomReferenceForm->isSubmitted() && $orderCustomReferenceForm->isValid()) {
            $quoteResponseTransfer = $this->getFactory()
                ->getOrderCustomReferenceClient()
                ->setOrderCustomReference(
                    $orderCustomReferenceForm->getData()[OrderCustomReferenceForm::FIELD_ORDER_CUSTOM_REFERENCE] ?? '',
                    $quoteClient->getQuote()
                );

            if ($quoteResponseTransfer->getIsSuccessful()) {
                $quoteClient->setQuote($quoteResponseTransfer->getQuoteTransfer());
                $this->addSuccessMessage(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_SAVED);
            }
            $this->handleQuoteResponseTransferErrors($quoteResponseTransfer);
        }

        return $this->redirectResponseExternal(
            $orderCustomReferenceForm->getData()[OrderCustomReferenceForm::FIELD_BACK_URL]
        );
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
