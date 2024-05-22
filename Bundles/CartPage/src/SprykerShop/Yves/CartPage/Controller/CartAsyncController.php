<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Controller;

use Generated\Shared\Transfer\CartPageViewArgumentsTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\MiniCartViewTransfer;
use Generated\Shared\Transfer\QuoteTransfer;
use Spryker\Yves\Kernel\View\View;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 * @method \SprykerShop\Yves\CartPage\CartPageConfig getConfig()
 */
class CartAsyncController extends AbstractCartController
{
    /**
     * @var string
     */
    protected const MESSAGE_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @var string
     */
    protected const MINI_CART_ASYNC_VIEW_TEMPLATE_PATH = '@CartPage/views/mini-cart-async/mini-cart-async.twig';

    /**
     * @var string
     */
    protected const PARAM_COUNTER_ONLY = 'counterOnly';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function removeAction(Request $request, string $sku)
    {
        if (!$this->canRemoveCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->getMessagesJsonResponse();
        }

        $form = $this->getFactory()->createCartPageFormFactory()->getRemoveForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getMessagesJsonResponse();
        }

        $this->getFactory()
            ->getCartClient()
            ->removeItem($sku, $request->get(static::REQUEST_PARAMETER_GROUP_KEY, null));

        $this->addMessages();

        return $this->viewAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function changeQuantityAction(Request $request, $sku)
    {
        $quantity = $request->get(static::REQUEST_PARAMETER_QUANTITY, 1);

        if (!$this->canChangeCartItem($quantity)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->getMessagesJsonResponse();
        }

        $form = $this->getFactory()->createCartPageFormFactory()->getCartChangeQuantityForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getMessagesJsonResponse();
        }

        $this->getFactory()
            ->getCartClient()
            ->changeItemQuantity($sku, $request->get(static::REQUEST_PARAMETER_GROUP_KEY), $quantity);

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $this->viewAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function quickAddAction(Request $request)
    {
        $sku = $request->get(static::REQUEST_PARAMETER_SKU);
        $quantity = (int)$request->get(static::REQUEST_PARAMETER_QUANTITY, 1);

        if (!$this->canAddCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->getMessagesJsonResponse();
        }

        $itemTransfer = (new ItemTransfer())
            ->setSku($sku)
            ->setQuantity($quantity)
            ->addNormalizableField(static::FIELD_QUANTITY_TO_NORMALIZE)
            ->setGroupKeyPrefix(uniqid('', true));

        $itemTransfer = $this->executePreAddToCartPlugins($itemTransfer, $request->request->all());

        $this->getFactory()
            ->getCartClient()
            ->addItem($itemTransfer);

        $this->addMessages();

        return $this->viewAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addAction(Request $request, $sku)
    {
        if (!$this->canAddCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->getMessagesJsonResponse();
        }

        $addToCartForm = $this->getFactory()->createCartPageFormFactory()->getAddToCartForm()->handleRequest($request);

        if (!$addToCartForm->isSubmitted() || !$addToCartForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->getMessagesJsonResponse();
        }

        $quantity = $request->get(static::REQUEST_PARAMETER_QUANTITY, 1);
        $itemTransfer = (new ItemTransfer())->setSku($sku)->setQuantity($quantity);
        $this->addProductOptions($request->get(static::REQUEST_PARAMETER_PRODUCT_OPTION, []), $itemTransfer);

        $itemTransfer = $this->executePreAddToCartPlugins($itemTransfer, $request->request->all());

        $this->getFactory()
            ->getCartClient()
            ->addItem($itemTransfer, $request->request->all());

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $this->viewAction($request);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\JsonResponse
     */
    public function updateAction(Request $request, string $sku)
    {
        $quantity = $request->get(static::REQUEST_PARAMETER_QUANTITY, 1);

        if (!$this->canChangeCartItem($quantity)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->getMessagesJsonResponse();
        }

        $isItemReplacedInCart = $this->replaceItem($sku, (int)$quantity, $request);

        if ($isItemReplacedInCart) {
            return $this->viewAction($request);
        }

        $this->addInfoMessage(static::MESSAGE_ITEM_ATTRIBUTES_NEEDED);

        return $this->viewAction(
            $request,
            $this->getFactory()
                ->createCartItemsAttributeProvider()
                ->formatUpdateActionResponse($sku, $request->get(static::REQUEST_PARAMETER_SELECTED_ATTRIBUTES, [])),
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param array<string, mixed> $selectedAttributes
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function viewAction(Request $request, array $selectedAttributes = []): View
    {
        return $this->view(
            $this->executeViewAction($selectedAttributes ?: $request->get(static::REQUEST_PARAMETER_SELECTED_ATTRIBUTES, [])),
            $this->getFactory()->getCartPageWidgetPlugins(),
            '@CartPage/views/cart-async/cart-async.twig',
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function miniCartViewAction(Request $request): Response
    {
        $cartClient = $this->getFactory()->getCartClient();
        $quoteTransfer = $cartClient->getQuote();
        $cartQuantity = $cartClient->getItemCount();
        $quoteTransfer->setTotalItemCount($cartQuantity);

        $content = $this->getTwig()->render(static::MINI_CART_ASYNC_VIEW_TEMPLATE_PATH, [
            'cartQuantity' => $cartQuantity,
        ]);

        $miniCartViewTransfer = (new MiniCartViewTransfer())
            ->setContent($content)
            ->setCounterOnly($request->get(static::PARAM_COUNTER_ONLY));
        $miniCartViewTransfer = $this->executeMiniCartViewExpanderPlugins($miniCartViewTransfer, $quoteTransfer);

        return $this->renderView('@CartPage/views/cart-async/mini-cart-view.twig', [
            'content' => $miniCartViewTransfer->getContent(),
        ]);
    }

    /**
     * @param array<string, mixed> $selectedAttributes
     *
     * @return array<string, mixed>
     */
    protected function executeViewAction(array $selectedAttributes = []): array
    {
        $cartPageViewArgumentsTransfer = new CartPageViewArgumentsTransfer();
        $cartPageViewArgumentsTransfer
            ->setWithItems(true)
            ->setLocale($this->getLocale())
            ->setSelectedAttributes($selectedAttributes);

        $viewData = $this->getFactory()->createCartPageView()->getViewData($cartPageViewArgumentsTransfer);

        $viewData['isCartItemsViaAjaxLoadEnabled'] = false;
        $viewData['isUpsellingProductsViaAjaxEnabled'] = false;

        return $viewData;
    }

    /**
     * @return void
     */
    protected function addMessages(): void
    {
        $zedRequestClient = $this->getFactory()->getZedRequestClient();

        $this->addErrorMessages($zedRequestClient->getLastResponseErrorMessages());
        $this->addSuccessMessages($zedRequestClient->getLastResponseSuccessMessages());
        $this->addInfoMessages($zedRequestClient->getLastResponseInfoMessages());
    }

    /**
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $messageTransfers
     *
     * @return void
     */
    protected function addInfoMessages(array $messageTransfers): void
    {
        foreach ($messageTransfers as $messageTransfer) {
            $this->addInfoMessage($messageTransfer->getValue());
        }
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

    /**
     * @param \Generated\Shared\Transfer\MiniCartViewTransfer $miniCartViewTransfer
     * @param \Generated\Shared\Transfer\QuoteTransfer $quoteTransfer
     *
     * @return \Generated\Shared\Transfer\MiniCartViewTransfer
     */
    protected function executeMiniCartViewExpanderPlugins(
        MiniCartViewTransfer $miniCartViewTransfer,
        QuoteTransfer $quoteTransfer
    ): MiniCartViewTransfer {
        foreach ($this->getFactory()->getMiniCartViewExpanderPlugins() as $miniCartViewExpanderPlugin) {
            $miniCartViewTransfer = $miniCartViewExpanderPlugin->expand($miniCartViewTransfer, $quoteTransfer);
        }

        return $miniCartViewTransfer;
    }
}
