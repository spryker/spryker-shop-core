<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\MultiCartPage\MultiCartPageFactory getFactory()
 */
class MultiCartController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $quoteForm = $this->getFactory()
            ->getQuoteForm()
            ->handleRequest($request);

        if ($quoteForm->isSubmitted() && $quoteForm->isValid()) {
            $quoteTransfer = $quoteForm->getData();
            $quoteTransfer->setCustomer(
                $this->getFactory()->getCustomerClient()->getCustomer()
            );
            $quoteResponseTransfer = $this->getFactory()->getMultiCartClient()
                ->createCart($quoteTransfer);
            if ($quoteResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
            }
        }

        $data = [
            'quoteForm' => $quoteForm->createView(),
        ];

        return $this->view($data, [], '@MultiCartPage/views/multi-cart/cart-create.twig');
    }

    /**
     * @param string $quoteName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($quoteName, Request $request)
    {
        $quoteForm = $this->getFactory()
            ->getQuoteForm()
            ->handleRequest($request);

        if ($quoteForm->isSubmitted() && $quoteForm->isValid()) {
            $quoteTransfer = $quoteForm->getData();
            $this->getFactory()->createQuoteWriter()
                ->updateQuote($quoteTransfer);
        }

        $data = [
            'quoteForm' => $quoteForm->createView(),
        ];

        return $this->view($data, [], '@MultiCartPage/views/multi-cart/cart-update.twig');
    }

    /**
     * @param string $quoteName
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($quoteName)
    {
        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setName($quoteName);
        $quoteTransfer->setCustomer(
            $this->getFactory()->getCustomerClient()->getCustomer()
        );

        $this->getFactory()->createQuoteWriter()->deleteQuote($quoteTransfer);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }
}
