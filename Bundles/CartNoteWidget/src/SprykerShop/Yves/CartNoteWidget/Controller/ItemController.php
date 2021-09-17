<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartNoteWidget\Controller;

use Spryker\Shared\CartNote\Code\Messages;
use Spryker\Yves\Kernel\Controller\AbstractController;
use SprykerShop\Yves\CartNoteWidget\Form\QuoteItemCartNoteForm;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartNoteWidget\CartNoteWidgetFactory getFactory()
 */
class ItemController extends AbstractController
{
    /**
     * @var string
     */
    public const REQUEST_HEADER_REFERER = 'referer';

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin::ROUTE_NAME_CART
     * @var string
     */
    protected const ROUTE_NAME_CART = 'cart';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $form = $this->getFactory()
            ->getCartNoteQuoteItemForm()
            ->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $note = (string)$form->get(QuoteItemCartNoteForm::FIELD_CART_NOTE)->getData();
            $sku = (string)$form->get(QuoteItemCartNoteForm::FIELD_SKU)->getData();
            $groupKey = (string)$form->get(QuoteItemCartNoteForm::FIELD_GROUP_KEY)->getData();

            $quoteResponseTransfer = $this->getFactory()
                ->getCartNoteClient()
                ->setNoteToQuoteItem($note, $sku, $groupKey);
            if ($quoteResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(
                    $this->getSuccessMessage($sku)
                );
            }
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
        if ($request->headers->has(static::REQUEST_HEADER_REFERER)) {
            return $request->headers->get(static::REQUEST_HEADER_REFERER);
        }

        return static::ROUTE_NAME_CART;
    }

    /**
     * @param string $sku
     *
     * @return string
     */
    protected function getSuccessMessage($sku): string
    {
        return $this->getFactory()->getGlossaryClient()
            ->translate(Messages::MESSAGE_CART_NOTE_ADDED_TO_ITEM_SUCCESS, $this->getLocale(), ['%sku%' => $sku]);
    }
}
