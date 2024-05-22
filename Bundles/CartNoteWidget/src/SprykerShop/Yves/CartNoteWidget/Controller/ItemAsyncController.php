<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Controller;

use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\CartNoteWidget\Form\QuoteItemCartNoteForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartNoteWidget\CartNoteWidgetFactory getFactory()
 */
class ItemAsyncController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageAsyncRouteProviderPlugin::ROUTE_NAME_CART_ASYNC_VIEW
     *
     * @var string
     */
    protected const ROUTE_NAME_CART_ASYNC_VIEW = 'cart/async/view';

    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @var string
     */
    protected const MESSAGE_CART_NOTE_ADDED_TO_ITEM_SUCCESS = 'cart_note.cart_page.cart.note_added';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @var string
     */
    protected const KEY_MESSAGES = 'messages';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addAction(Request $request)
    {
        $form = $this->getFactory()
            ->getCartNoteQuoteItemForm()
            ->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->jsonResponse([
                static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            ]);
        }

        $cartNote = (string)$form->get(QuoteItemCartNoteForm::FIELD_CART_NOTE)->getData();
        $sku = (string)$form->get(QuoteItemCartNoteForm::FIELD_SKU)->getData();
        $groupKey = (string)$form->get(QuoteItemCartNoteForm::FIELD_GROUP_KEY)->getData();

        $quoteResponseTransfer = $this->getFactory()
            ->getCartNoteClient()
            ->setNoteToQuoteItem($cartNote, $sku, $groupKey);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage($this->getSuccessMessage($sku));
        }

        return $this->redirectResponseInternal(static::ROUTE_NAME_CART_ASYNC_VIEW);
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    protected function getSuccessMessage(string $sku): string
    {
        return $this->getFactory()
            ->getGlossaryClient()
            ->translate(static::MESSAGE_CART_NOTE_ADDED_TO_ITEM_SUCCESS, $this->getLocale(), ['%sku%' => $sku]);
    }
}
