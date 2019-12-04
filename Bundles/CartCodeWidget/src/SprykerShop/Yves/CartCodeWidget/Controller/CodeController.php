<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Controller;

use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\CartCodeResponseTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CartCodeWidget\Form\CartCodeForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartCodeWidget\CartCodeWidgetFactory getFactory()
 */
class CodeController extends AbstractController
{
    public const PARAM_CODE = 'code';

    protected const MESSAGE_TYPE_SUCCESS = 'success';
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()->getCartCodeForm()->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            return $this->redirectResponseExternal($request->headers->get('referer'));
        }

        $code = (string)$form->get(CartCodeForm::FIELD_CODE)->getData();

        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        $cartCodeResponseTransfer = $this->getFactory()
            ->getCartCodeClient()
            ->addCartCode($this->createCartCodeResponseTransfer($quoteTransfer, $code));

        return $this->redirectResponse($cartCodeResponseTransfer, $request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request)
    {
        $code = (string)$request->query->get(static::PARAM_CODE);
        if (empty($code)) {
            return $this->redirectResponseExternal($request->headers->get('referer'));
        }

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $cartCodeResponseTransfer = $this->getFactory()
            ->getCartCodeClient()
            ->removeCartCode($this->createCartCodeResponseTransfer($quoteTransfer, $code));

        return $this->redirectResponse($cartCodeResponseTransfer, $request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction(Request $request)
    {
        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        $cartCodeResponseTransfer = $this->getFactory()
            ->getCartCodeClient()
            ->clearCartCodes($this->createCartCodeResponseTransfer($quoteTransfer));

        return $this->redirectResponse($cartCodeResponseTransfer, $request);
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeResponseTransfer $cartCodeResponseTransfer
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function redirectResponse(
        CartCodeResponseTransfer $cartCodeResponseTransfer,
        Request $request
    ): RedirectResponse {
        $this->getFactory()->getQuoteClient()->setQuote($cartCodeResponseTransfer->getQuote());
        $this->getFactory()->getZedRequestClient()->addFlashMessagesFromLastZedRequest();

        foreach ($cartCodeResponseTransfer->getMessages() as $messageTransfer) {
            $this->handleMessage($messageTransfer);
        }

        return $this->redirectResponseExternal($request->headers->get('referer'));
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     * @param string|null $code
     *
     * @return \Generated\Shared\Transfer\CartCodeRequestTransfer
     */
    protected function createCartCodeResponseTransfer(
        QuoteTransfer $quoteTransfer,
        ?string $code = null
    ): CartCodeRequestTransfer {
        return (new CartCodeRequestTransfer())
            ->setQuote($quoteTransfer)
            ->setCartCode($code);
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer $messageTransfer
     *
     * @return void
     */
    protected function handleMessage(MessageTransfer $messageTransfer): void
    {
        switch ($messageTransfer->getType()) {
            case self::MESSAGE_TYPE_SUCCESS:
                $this->addSuccessMessage($messageTransfer->getValue());
                break;
            case self::MESSAGE_TYPE_ERROR:
                $this->addErrorMessage($messageTransfer->getValue());
                break;
            default:
                $this->addInfoMessage($messageTransfer->getValue());
        }
    }
}
