<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNotesWidget\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\CartNotesWidget\Form\QuoteCartNoteForm;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartNotesWidget\CartNotesWidgetFactory getFactory()
 */
class QuoteController extends AbstractController
{
    const MESSAGE_CART_NOTES_ADDED_TO_CART_SUCCESS = 'cart_notes.cart_page.cart.note_added';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $form = $this->getFactory()
            ->getCartNotesQuoteForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->get(QuoteCartNoteForm::FIELD_CART_NOTE)->getData();

            $this->getFactory()
                ->createCartNotesHandler()
                ->setNoteToQuote($note);
            $this->addSuccessMessage(static::MESSAGE_CART_NOTES_ADDED_TO_CART_SUCCESS);
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }
}
