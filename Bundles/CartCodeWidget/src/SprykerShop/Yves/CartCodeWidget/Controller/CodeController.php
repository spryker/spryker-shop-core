<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Controller;

use Generated\Shared\Transfer\CartCodeOperationMessageTransfer;
use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use SprykerShop\Yves\CartCodeWidget\Form\CartCodeForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartCodeWidget\CartCodeWidgetFactory getFactory()
 */
class CodeController extends AbstractController
{
    public const PARAM_CODE = 'code';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()
            ->getCartCodeForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = (string)$form->get(CartCodeForm::FIELD_CODE)->getData();

            $quoteTransfer = $this->getFactory()
                ->getQuoteClient()
                ->getQuote();

            $cartCodeOperationResultTransfers = $this->getFactory()
                ->getCartCodeClient()
                ->addCandidate($quoteTransfer, $code);

            $this->getFactory()
                ->getQuoteClient()
                ->setQuote($cartCodeOperationResultTransfers->getQuote());

            $this->getFactory()
                ->getZedRequestClient()
                ->addFlashMessagesFromLastZedRequest();

            $this->handleCartCodeOperationResult($cartCodeOperationResultTransfers);
        }

        return $this->redirectResponseExternal($request->headers->get('referer'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request)
    {
        $code = (string)$request->query->get(static::PARAM_CODE);
        if (!empty($code)) {
            $quoteTransfer = $this->getFactory()
                ->getQuoteClient()
                ->getQuote();

            $cartCodeOperationResultTransfers = $this->getFactory()
                ->getCartCodeClient()
                ->removeCode($quoteTransfer, $code);

            $this->getFactory()
                ->getQuoteClient()
                ->setQuote($cartCodeOperationResultTransfers->getQuote());

            $this->getFactory()
                ->getZedRequestClient()
                ->addFlashMessagesFromLastZedRequest();

            $this->handleCartCodeOperationResult($cartCodeOperationResultTransfers);
        }

        return $this->redirectResponseExternal($request->headers->get('referer'));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        $cartCodeOperationResultTransfers = $this->getFactory()
            ->getCartCodeClient()
            ->clearCodes($quoteTransfer);

        $this->getFactory()
            ->getQuoteClient()
            ->setQuote($cartCodeOperationResultTransfers->getQuote());

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        $this->handleCartCodeOperationResult($cartCodeOperationResultTransfers);

        return $this->redirectResponseExternal($request->headers->get('referer'));
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeOperationResultTransfer $cartCodeOperationResultTransfer
     *
     * @return void
     */
    protected function handleCartCodeOperationResult(CartCodeOperationResultTransfer $cartCodeOperationResultTransfer): void
    {
        foreach ($cartCodeOperationResultTransfer->getMessages() as $cartCodeOperationMessageTransfer) {
            $this->handleCartCodeOperationMessage($cartCodeOperationMessageTransfer);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeOperationMessageTransfer $cartCodeOperationMessageTransfer
     *
     * @return void
     */
    protected function handleCartCodeOperationMessage(CartCodeOperationMessageTransfer $cartCodeOperationMessageTransfer): void
    {
        if (!$cartCodeOperationMessageTransfer->getMessage()) {
            return;
        }

        if ($cartCodeOperationMessageTransfer->getIsSuccess()) {
            $this->addSuccessMessage($cartCodeOperationMessageTransfer->getMessage()->getValue());

            return;
        }

        $this->addErrorMessage($cartCodeOperationMessageTransfer->getMessage()->getValue());
    }
}
