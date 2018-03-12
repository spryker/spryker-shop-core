<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\CartNoteWidget\Form\QuoteCartNoteForm;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartNoteWidget\CartNoteWidgetFactory getFactory()
 */
class QuoteController extends AbstractController
{
    const MESSAGE_CART_NOTE_ADDED_TO_CART_SUCCESS = 'cart_note.cart_page.cart.note_added';
    const REQUEST_HEADER_PEFERER = 'referer';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $form = $this->getFactory()
            ->getCartNoteQuoteForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->get(QuoteCartNoteForm::FIELD_CART_NOTE)->getData();

            $this->getFactory()
                ->createCartNotesHandler()
                ->setNoteToQuote($note);
            $this->addSuccessMessage(static::MESSAGE_CART_NOTE_ADDED_TO_CART_SUCCESS);
        }

        return $this->redirectResponseExternal($this->getRefererUrl($request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|string
     */
    protected function getRefererUrl(Request $request)
    {
        if ($request->headers->has(static::REQUEST_HEADER_PEFERER)) {
            return $request->headers->get(static::REQUEST_HEADER_PEFERER);
        }

        return CartControllerProvider::ROUTE_CART;
    }
}
