<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartCodeWidget\Controller;

use SprykerShop\Yves\CartCodeWidget\Form\CartCodeForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartCodeWidget\CartCodeWidgetFactory getFactory()
 */
class CodeAsyncController extends AbstractCodeController
{
    /**
     * @var string
     */
    protected const PARAM_CODE = 'code';

    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()->getCartCodeForm()->handleRequest($request);
        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getRedirectResponse($request);
        }

        $code = (string)$form->get(CartCodeForm::FIELD_CODE)->getData();

        $quoteTransfer = $this->getFactory()
            ->getQuoteClient()
            ->getQuote();

        $cartCodeResponseTransfer = $this->getFactory()
            ->getCartCodeClient()
            ->addCartCode($this->createCartCodeRequestTransfer($quoteTransfer, $code));

        $this->processErrorResponseMessage($cartCodeResponseTransfer);

        return $this->redirectResponse($cartCodeResponseTransfer, $request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Request $request)
    {
        $cartCodeRemoveForm = $this->getFactory()->getCartCodeRemoveForm()->handleRequest($request);

        if (!$cartCodeRemoveForm->isSubmitted() || !$cartCodeRemoveForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getRedirectResponse($request);
        }

        $code = (string)$request->query->get(static::PARAM_CODE);
        if (!$code) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getRedirectResponse($request);
        }

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();
        $cartCodeResponseTransfer = $this->getFactory()
            ->getCartCodeClient()
            ->removeCartCode($this->createCartCodeRequestTransfer($quoteTransfer, $code));

        return $this->redirectResponse($cartCodeResponseTransfer, $request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function clearAction(Request $request)
    {
        $cartCodeClearForm = $this->getFactory()->getCartCodeClearForm()->handleRequest($request);

        if (!$cartCodeClearForm->isSubmitted() || !$cartCodeClearForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getRedirectResponse($request);
        }

        $quoteTransfer = $this->getFactory()->getQuoteClient()->getQuote();

        $cartCodeResponseTransfer = $this->getFactory()
            ->getCartCodeClient()
            ->clearCartCodes($this->createCartCodeRequestTransfer($quoteTransfer));

        return $this->redirectResponse($cartCodeResponseTransfer, $request);
    }
}
