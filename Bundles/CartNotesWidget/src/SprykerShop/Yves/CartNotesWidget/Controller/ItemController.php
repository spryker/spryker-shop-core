<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNotesWidget\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\CartNotesWidget\Form\QuoteItemCartNoteForm;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartNotesWidget\CartNotesWidgetFactory getFactory()
 */
class ItemController extends AbstractController
{
    const MESSAGE_CART_NOTES_ADDED_SUCCESS_TO_ITEM = 'cart_notes.cart_page.item.note_added';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $form = $this->getFactory()
            ->getCartNotesQuoteItemForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note = $form->get(QuoteItemCartNoteForm::FIELD_CART_NOTE)->getData();
            $sku = $form->get(QuoteItemCartNoteForm::FIELD_SKU)->getData();
            $groupKey = $form->get(QuoteItemCartNoteForm::FIELD_GROUP_KEY)->getData();

            $this->getFactory()
                ->createCartNotesHandler()
                ->setNoteToQuoteItem($note, $sku, $groupKey);
            $this->addSuccessMessage(static::MESSAGE_CART_NOTES_ADDED_SUCCESS_TO_ITEM);
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }
}
