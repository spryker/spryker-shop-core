<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage\Controller;

use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Client\SharedCart\Plugin\WriteSharedCartPermissionPlugin;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\MultiCartPage\Plugin\Provider\MultiCartPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\MultiCartPage\MultiCartPageFactory getFactory()
 */
class MultiCartController extends AbstractController
{
    use PermissionAwareTrait;

    public const GLOSSARY_KEY_CART_UPDATED_SUCCESS = 'multi_cart_widget.cart.updated.success';
    public const MESSAGE_PERMISSION_FAILED = 'global.permission.failed';
    public const MESSAGE_CART_CLEAR_SUCCESS = 'multi_cart_page.cart_clear.success';

    /**
     * @deprecated Will be removed without replacement.
     */
    public const GLOSSARY_KEY_CART_WAS_DELETED = 'multi_cart_widget.cart.was-deleted-before';

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
                return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
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

        if ($quoteTransfer === null || !$this->checkAccessToCart($quoteTransfer)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

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
            ->setDefaultQuote($quoteTransfer);

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function duplicateAction(int $idQuote)
    {
        return $this->executeDuplicateAction($idQuote);
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeDuplicateAction(int $idQuote): RedirectResponse
    {
        $quoteTransfer = $this->findQuoteOrFail($idQuote);

        if (!$this->checkAccessToCart($quoteTransfer)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_INDEX);
        }

        $idNewQuote = $this->getFactory()
            ->getMultiCartClient()
            ->duplicateQuote($quoteTransfer)
            ->getQuoteTransfer()
            ->getIdQuote();

        return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_UPDATE, [
                MultiCartPageControllerProvider::PARAM_ID_QUOTE => $idNewQuote,
            ]
        );
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function clearAction(int $idQuote)
    {
        return $this->executeClearAction($idQuote);
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeClearAction(int $idQuote): RedirectResponse
    {
        $quoteTransfer = $this->findQuoteOrFail($idQuote);

        if (!$this->checkAccessToCart($quoteTransfer)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_INDEX);
        }

        $quoteResponseTransfer = $this->getFactory()
            ->getMultiCartClient()
            ->clearQuote($quoteTransfer);

        if ($quoteResponseTransfer->getIsSuccessful()) {
            $this->addSuccessMessage(static::MESSAGE_CART_CLEAR_SUCCESS);
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(int $idQuote)
    {
        return $this->executeDeleteAction($idQuote);
    }

    /**
     * @param int $idQuote
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeDeleteAction(int $idQuote): RedirectResponse
    {
        $quoteTransfer = $this->findQuoteOrFail($idQuote);

        if (!$this->checkAccessToCart($quoteTransfer)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

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
            $this->getFactory()->getMultiCartListWidgetPlugins(),
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
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmDeleteAction(int $idQuote)
    {
        $response = $this->executeConfirmDeleteAction($idQuote);

        if (!is_array($response)) {
            return $response;
        }

        $widgetPlugins = $this->getFactory()
            ->getCartDeleteCompanyUsersListWidgetPlugins();

        return $this->view($response, $widgetPlugins, '@MultiCartPage/views/cart-delete/cart-delete.twig');
    }

    /**
     * @param int $idQuote
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeConfirmDeleteAction(int $idQuote)
    {
        $quoteTransfer = $this->findQuoteOrFail($idQuote);

        if (!$this->checkAccessToCart($quoteTransfer)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(MultiCartPageControllerProvider::ROUTE_MULTI_CART_INDEX);
        }

        return [
            'cart' => $quoteTransfer,
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
    protected function checkAccessToCart(QuoteTransfer $quoteTransfer): bool
    {
        return $this->can(
            WriteSharedCartPermissionPlugin::KEY,
            $quoteTransfer->getIdQuote()
        );
    }
}
