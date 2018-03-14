<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Controller;

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
            $quoteTransfer = $quoteForm->getData();
            $quoteTransfer->setCustomer(
                $this->getFactory()->getCustomerClient()->getCustomer()
            );
            $quoteResponseTransfer = $this->getFactory()->createCartOperations()
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
     * @param string $quoteName
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($quoteName, Request $request)
    {
        $quoteForm = $this->getFactory()
            ->getQuoteForm($quoteName)
            ->handleRequest($request);

        $quoteTransfer = $quoteForm->getData();
        if ($quoteForm->isSubmitted() && $quoteForm->isValid()) {
            $quoteTransfer->setCustomer(
                $this->getFactory()->getCustomerClient()->getCustomer()
            );
            $quoteResponseTransfer = $this->getFactory()->createCartOperations()
                ->updateQuote($quoteTransfer);
            if ($quoteResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponseInternal(
                    MultiCartPageControllerProvider::ROUTE_MULTI_CART_UPDATE,
                    ['quoteName' => $quoteResponseTransfer->getQuoteTransfer()->getName()]
                );
            }
        }

        $data = [
            'cart' => $quoteTransfer,
            'quoteForm' => $quoteForm->createView(),
        ];

        return $this->view($data, [], '@MultiCartPage/views/multi-cart/cart-update.twig');
    }

    /**
     * @param string $quoteName
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setActiveAction($quoteName)
    {
        $quoteTransfer = $this->getFactory()->getMultiCartClient()->findQuoteByName($quoteName);
        $this->getFactory()->createCartOperations()
            ->setActiveQuote($quoteTransfer);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $quoteName
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function duplicateAction($quoteName)
    {
        $quoteTransfer = $this->getFactory()->getMultiCartClient()->findQuoteByName($quoteName);
        $this->getFactory()->createCartOperations()
            ->duplicateQuote($quoteTransfer);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $quoteName
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction($quoteName)
    {
        $quoteTransfer = $this->getFactory()->getMultiCartClient()->findQuoteByName($quoteName);
        $this->getFactory()->createCartOperations()
            ->clearQuote($quoteTransfer);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $quoteName
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction($quoteName)
    {
        $multiCartClient = $this->getFactory()->getMultiCartClient();
        $quoteTransfer = $multiCartClient->findQuoteByName($quoteName);
        $quoteTransfer->setCustomer(
            $this->getFactory()->getCustomerClient()->getCustomer()
        );

        $this->getFactory()->createCartOperations()->deleteQuote($quoteTransfer);
        $customerQuoteTransferList = $multiCartClient->getQuoteCollection()->getQuotes();
        if ($quoteTransfer->getIsActive() && count($customerQuoteTransferList)) {
            $quoteTransfer = reset($customerQuoteTransferList);

            return $this->redirectResponseInternal(
                MultiCartPageControllerProvider::ROUTE_MULTI_CART_SET_ACTIVE,
                ['quoteName' => $quoteTransfer->getName()]
            );
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }
}
