<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\OrderCustomReferenceWidget\Controller;

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
    protected const GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH = 'order_custom_reference.validation.error.message_invalid_length';

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

        $isOrderCustomReferenceLengthValid = $this->getFactory()
            ->createOrderCustomReferenceValidator()
            ->isOrderCustomReferenceLengthValid($orderCustomReference);

        if ($isOrderCustomReferenceLengthValid) {
            $this->addErrorMessage(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_MESSAGE_INVALID_LENGTH);

            return $this->redirectResponseExternal($backUrl);
        }

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $quoteTransfer->setOrderCustomReference($orderCustomReference);

        $quoteResponseTransfer = $this->getFactory()
            ->getOrderCustomReferenceClient()
            ->setOrderCustomReference($quoteTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->getFactory()->getQuoteClient()->setQuote($quoteResponseTransfer->getQuoteTransfer());
            $this->addSuccessMessage(static::GLOSSARY_KEY_ORDER_CUSTOM_REFERENCE_SAVED);
        }

        return $this->redirectResponseExternal($backUrl);
    }
}
