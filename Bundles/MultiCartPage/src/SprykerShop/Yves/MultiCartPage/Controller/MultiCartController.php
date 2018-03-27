<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\MultiCartPage\Plugin\Provider\MultiCartPageControllerProvider;
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
            $customerTransfer = $this->getFactory()
                ->getCustomerClient()
                ->getCustomer();

            $quoteTransfer = $quoteForm->getData();
            $quoteTransfer->setCustomer($customerTransfer);

            $quoteResponseTransfer = $this->getFactory()
                ->createCartOperations()
                ->createQuote($quoteTransfer);

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
     * @param int $idQuote
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(int $idQuote, Request $request)
    {
        $quoteForm = $this->getFactory()
            ->getQuoteForm($idQuote)
            ->handleRequest($request);

        $quoteTransfer = $quoteForm->getData();
        if ($quoteForm->isSubmitted() && $quoteForm->isValid()) {
            $customerTransfer = $this->getFactory()
                ->getCustomerClient()
                ->getCustomer();

            $quoteTransfer->setCustomer($customerTransfer);

            $quoteResponseTransfer = $this->getFactory()
                ->createCartOperations()
                ->updateQuote($quoteTransfer);

            if ($quoteResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_UPDATE, [
                    MultiCartPageControllerProvider::PARAM_ID_QUOTE => $quoteResponseTransfer->getQuoteTransfer()->getIdQuote(),
                ]);
            }
        }

        $data = [
            'cart' => $quoteTransfer,
            'quoteForm' => $quoteForm->createView(),
        ];

        return $this->view($data, [], '@MultiCartPage/views/multi-cart/cart-update.twig');
    }

    /**
     * @param int $idQuote
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setDefaultAction(int $idQuote)
    {
        $quoteTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->findQuoteById($idQuote);

        $this->getFactory()
            ->createCartOperations()
            ->setDefaultQuote($quoteTransfer);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param int $idQuote
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function duplicateAction(int $idQuote)
    {
        $quoteTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->findQuoteById($idQuote);

        $this->getFactory()
            ->createCartOperations()
            ->duplicateQuote($quoteTransfer);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction(int $idQuote)
    {
        $quoteTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->findQuoteById($idQuote);

        $this->getFactory()
            ->createCartOperations()
            ->clearQuote($quoteTransfer);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param int $idQuote
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(int $idQuote)
    {
        $multiCartClient = $this->getFactory()->getMultiCartClient();
        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        $quoteTransfer = new QuoteTransfer();
        $quoteTransfer->setIdQuote($idQuote);
        $quoteTransfer->setCustomer($customerTransfer);

        $this->getFactory()
            ->createCartOperations()
            ->deleteQuote($quoteTransfer);

        $customerQuoteTransferList = $multiCartClient->getQuoteCollection()->getQuotes();
        if ($quoteTransfer->getIsDefault() && count($customerQuoteTransferList)) {
            $quoteTransfer = reset($customerQuoteTransferList);

            return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_SET_DEFAULT, [
                MultiCartPageControllerProvider::PARAM_ID_QUOTE => $quoteTransfer->getIdQuote(),
            ]);
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }
}
