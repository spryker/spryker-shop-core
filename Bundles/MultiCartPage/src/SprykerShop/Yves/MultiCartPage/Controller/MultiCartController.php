<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\MultiCartPage\Plugin\Provider\MultiCartPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\MultiCartPage\MultiCartPageFactory getFactory()
 */
class MultiCartController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @uses \SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider::ROUTE_CART
     */
    protected const ROUTE_CART = 'cart';

    public const GLOSSARY_KEY_CART_UPDATED_SUCCESS = 'multi_cart_widget.cart.updated.success';

    protected const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';
    protected const GLOSSARY_KEY_CART_UPDATED_ERROR = 'multi_cart_widget.cart.updated.error';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $response = $this->executeCreateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@MultiCartPage/views/cart-create/cart-create.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateAction(Request $request)
    {
        $quoteForm = $this->getFactory()
            ->getQuoteForm()
            ->handleRequest($request);

        if ($quoteForm->isSubmitted() && $quoteForm->isValid()) {
            $quoteTransfer = $quoteForm->getData();

            $quoteResponseTransfer = $this->getFactory()
                ->getMultiCartClient()
                ->createQuote($quoteTransfer);

            if ($quoteResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponseInternal(static::ROUTE_CART);
            }
        }

        return [
            'quoteForm' => $quoteForm->createView(),
        ];
    }

    /**
     * @param int $idQuote
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(int $idQuote, Request $request)
    {
        $response = $this->executeUpdateAction($idQuote, $request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@MultiCartPage/views/cart-update/cart-update.twig');
    }

    /**
     * @param int $idQuote
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeUpdateAction(int $idQuote, Request $request)
    {
        $quoteForm = $this->getFactory()
            ->getQuoteForm($idQuote)
            ->handleRequest($request);

        $quoteTransfer = $quoteForm->getData();

        if (!$quoteTransfer || !$this->canWriteQuote($quoteTransfer)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_INDEX);
        }

        if ($quoteForm->isSubmitted() && $quoteForm->isValid()) {
            $quoteResponseTransfer = $this->getFactory()
                ->getMultiCartClient()
                ->updateQuote($quoteTransfer);

            if ($quoteResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(static::GLOSSARY_KEY_CART_UPDATED_SUCCESS);

                return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_INDEX);
            }

            $this->addErrorMessage(static::GLOSSARY_KEY_CART_UPDATED_ERROR);

            return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_INDEX);
        }

        return [
            'cart' => $quoteTransfer,
            'quoteForm' => $quoteForm->createView(),
        ];
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function setDefaultAction(int $idQuote)
    {
        $quoteTransfer = $this->findQuoteOrFail($idQuote);

        $this->getFactory()
            ->getMultiCartClient()
            ->setActiveQuote($quoteTransfer);

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function duplicateAction(int $idQuote)
    {
        $quoteTransfer = $this->findQuoteOrFail($idQuote);

        $idNewQuote = $this->getFactory()
            ->getMultiCartClient()
            ->duplicateQuote($quoteTransfer)
            ->getQuoteTransfer()
            ->getIdQuote();

        return $this->redirectResponseInternal(
            MultiCartPageControllerProvider::ROUTE_MULTI_CART_UPDATE,
            [MultiCartPageControllerProvider::PARAM_ID_QUOTE => $idNewQuote]
        );
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction(int $idQuote)
    {
        $quoteTransfer = $this->findQuoteOrFail($idQuote);

        if (!$this->isQuoteEditable($quoteTransfer) || !$this->can('RemoveCartItemPermissionPlugin')) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->redirectResponseInternal(static::ROUTE_CART);
        }

        $quoteResponseTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->clearQuote($quoteTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage('multi_cart_page.cart_clear.success');
        }

        return $this->redirectResponseInternal(static::ROUTE_CART);
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(int $idQuote)
    {
        $quoteTransfer = $this->findQuoteOrFail($idQuote);

        if (!$this->canWriteQuote($quoteTransfer)) {
            $this->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);

            return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_INDEX);
        }

        $this->getFactory()->getMultiCartClient()->deleteQuote($quoteTransfer);

        return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_INDEX);
    }

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction()
    {
        $response = $this->executeIndexAction();

        return $this->view(
            $response,
            [],
            '@MultiCartPage/views/cart/cart.twig'
        );
    }

    /**
     * @return array
     */
    protected function executeIndexAction(): array
    {
        $this->getFactory()->getCartClient()->validateQuote();
        $quoteCollectionTransfer = $this->getFactory()->getMultiCartClient()->getQuoteCollection();

        return [
            'quoteCollection' => $quoteCollectionTransfer->getQuotes(),
            'isQuoteDeletable' => $this->getFactory()->getMultiCartClient()->isQuoteDeletable(),
        ];
    }

    /**
     * @param int $idQuote
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function confirmDeleteAction(int $idQuote)
    {
        $viewData = $this->executeConfirmDeleteAction($idQuote);

        return $this->view($viewData, [], '@MultiCartPage/views/cart-delete/cart-delete.twig');
    }

    /**
     * @param int $idQuote
     *
     * @return array
     */
    protected function executeConfirmDeleteAction(int $idQuote): array
    {
        return [
            'cart' => $this->findQuoteOrFail($idQuote),
        ];
    }

    /**
     * @param int $idQuote
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Generated\Shared\Transfer\QuoteTransfer
     */
    protected function findQuoteOrFail(int $idQuote): QuoteTransfer
    {
        $quoteTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->findQuoteById($idQuote);

        if (!$quoteTransfer) {
            throw new NotFoundHttpException();
        }

        return $quoteTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function canWriteQuote(QuoteTransfer $quoteTransfer): bool
    {
        return $quoteTransfer->getCustomerReference() === $quoteTransfer->getCustomer()->getCustomerReference()
            || $this->can('WriteSharedCartPermissionPlugin', $quoteTransfer->getIdQuote());
    }

    /**
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return bool
     */
    protected function isQuoteEditable(QuoteTransfer $quoteTransfer): bool
    {
        return $this->getFactory()
            ->getQuoteClient()
            ->isQuoteEditable($quoteTransfer);
    }
}
