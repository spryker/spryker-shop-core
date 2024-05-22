<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListWidget\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use SprykerShop\Yves\ShoppingListWidget\Plugin\Router\ShoppingListWidgetAsyncRouteProviderPlugin;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\ShoppingListWidget\ShoppingListWidgetFactory getFactory()
 */
class CartToShoppingListAsyncController extends AbstractController
{
    /**
     * @var string
     */
    protected const PARAM_ID_QUOTE = 'idQuote';

    /**
     * @var string
     */
    protected const PARAM_SHOPPING_LIST_FROM_CART_FORM = 'shopping_list_from_cart_form';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_SUCCESS = 'shopping_list.cart.items_add.success';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_FAILED = 'shopping_list.cart.items_add.failed';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @var string
     */
    protected const KEY_CONTENT = 'content';

    /**
     * @var string
     */
    protected const KEY_MESSAGES = 'messages';

    /**
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();

        $customerTransfer = $this->getFactory()
            ->getCustomerClient()
            ->getCustomer();

        if ($customerTransfer === null || !$customerTransfer->getCompanyUserTransfer()) {
            throw new NotFoundHttpException('Only company users are allowed to access this page');
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createFromCartAction(Request $request)
    {
        $idQuote = $request->get(static::PARAM_SHOPPING_LIST_FROM_CART_FORM)[static::PARAM_ID_QUOTE];

        $shoppingListFromCartForm = $this->getFactory()
            ->getShoppingListFromCartForm($idQuote)
            ->handleRequest($request);

        if (!$shoppingListFromCartForm->isSubmitted() || !$shoppingListFromCartForm->isValid()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_FAILED);

            return $this->getMessagesJsonResponse();
        }

        $this->getFactory()
            ->createCreateFromCartHandler()
            ->createShoppingListFromCart($shoppingListFromCartForm);

        $this->addSuccessMessage(static::GLOSSARY_KEY_SHOPPING_LIST_CART_ITEMS_ADD_SUCCESS);

        return $this->redirectResponseInternal(
            ShoppingListWidgetAsyncRouteProviderPlugin::ROUTE_NAME_SHOPPING_LIST_ASYNC_CREATE_FROM_CART_VIEW,
            [static::PARAM_ID_QUOTE => $idQuote],
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function viewAction(Request $request): JsonResponse
    {
        $idQuote = $request->get(static::PARAM_ID_QUOTE);
        $shoppingListFromCartForm = $this->getFactory()
            ->getShoppingListFromCartForm($idQuote)
            ->handleRequest($request);

        return $this->jsonResponse([
            static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            static::KEY_CONTENT => $this->getTwig()->render(
                '@ShoppingListWidget/views/create-shopping-list-from-cart-async/create-shopping-list-from-cart-async.twig',
                $this->getViewData($shoppingListFromCartForm),
            ),
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $shoppingListFromCartForm
     *
     * @return array<string, mixed>
     */
    protected function getViewData(FormInterface $shoppingListFromCartForm): array
    {
        $isWidgetVisible = $this->getIsWidgetVisible();

        return [
            'isVisible' => $isWidgetVisible,
            'form' => $isWidgetVisible ? $shoppingListFromCartForm->createView() : null,
        ];
    }

    /**
     * @return bool
     */
    protected function getIsWidgetVisible(): bool
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        return $customerTransfer && $customerTransfer->getCompanyUserTransfer();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    protected function getMessagesJsonResponse(): JsonResponse
    {
        return $this->jsonResponse([
            static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
        ]);
    }
}
