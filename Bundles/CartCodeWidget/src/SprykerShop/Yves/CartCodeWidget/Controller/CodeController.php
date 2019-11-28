<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Controller;

use Generated\Shared\Transfer\CartCodeOperationResultTransfer;
use Generated\Shared\Transfer\CartCodeRequestTransfer;
use Generated\Shared\Transfer\MessageTransfer;
use SprykerShop\Yves\CartCodeWidget\Form\CartCodeForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
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
        $form = $this->getFactory()
            ->getCartCodeForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $code = (string)$form->get(CartCodeForm::FIELD_CODE)->getData();

            $quoteTransfer = $this->getFactory()
                ->getQuoteClient()
                ->getQuote();

            $cartCodeResponseTransfer = $this->getFactory()
                ->getCartCodeClient()
                ->addCartCode(
                    (new CartCodeRequestTransfer())
                        ->setCartCode($code)
                        ->setQuote($quoteTransfer)
                );

            file_put_contents('vcv11.txt', print_r($cartCodeResponseTransfer, 1));
            $this->getFactory()
                ->getQuoteClient()
                ->setQuote($cartCodeResponseTransfer->getQuote());

            $this->getFactory()
                ->getZedRequestClient()
                ->addFlashMessagesFromLastZedRequest();

            $this->handleCartCodeOperationResult($cartCodeResponseTransfer);
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

            $cartCodeResponseTransfer = $this->getFactory()
                ->getCartCodeClient()
                ->removeCartCode(
                    (new CartCodeRequestTransfer())
                        ->setCartCode($code)
                        ->setQuote($quoteTransfer)
                );

            $this->getFactory()
                ->getQuoteClient()
                ->setQuote($cartCodeResponseTransfer->getQuote());

            $this->getFactory()
                ->getZedRequestClient()
                ->addFlashMessagesFromLastZedRequest();

            $this->handleCartCodeOperationResult($cartCodeResponseTransfer);
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

        $cartCodeResponseTransfer = $this->getFactory()
            ->getCartCodeClient()
            ->clearCartCodes(
                (new CartCodeRequestTransfer())
                    ->setQuote($quoteTransfer)
            );

        $this->getFactory()
            ->getQuoteClient()
            ->setQuote($cartCodeResponseTransfer->getQuote());

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        $this->handleCartCodeOperationResult($cartCodeResponseTransfer);

        return $this->redirectResponseExternal($request->headers->get('referer'));
    }

    /**
     * @param \Generated\Shared\Transfer\CartCodeOperationResultTransfer $cartCodeOperationResultTransfer
     *
     * @return void
     */
    protected function handleCartCodeOperationResult(CartCodeOperationResultTransfer $cartCodeOperationResultTransfer): void
    {
        foreach ($cartCodeOperationResultTransfer->getMessages() as $messageTransfer) {
            $this->handleMessage($messageTransfer);
        }
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
