<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Controller;

use Generated\Shared\Transfer\CartPageViewArgumentsTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Shared\CartPage\Plugin\AddCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\ChangeCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartController extends AbstractController
{
    use PermissionAwareTrait;

    public const MESSAGE_PERMISSION_FAILED = 'global.permission.failed';

    public const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    public const PARAM_ITEMS = 'items';

    protected const REQUEST_PARAMETER_SKU = 'sku';
    protected const REQUEST_PARAMETER_QUANTITY = 'quantity';
    protected const REQUEST_PARAMETER_TOKEN = '_token';

    protected const FIELD_QUANTITY_TO_NORMALIZE = 'quantity';

    protected const KEY_CODE = 'code';
    protected const KEY_MESSAGES = 'messages';

    protected const CSRF_TOKEN_ID = 'add-to-cart-ajax';
    protected const MESSAGE_TYPE_ERROR = 'error';
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';
    protected const GLOSSARY_KEY_ERROR_MESSAGE_UNEXPECTED_ERROR = 'cart_page.error_message.unexpected_error';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        $viewData = $this->executeIndexAction($request->get('selectedAttributes', []));
        $viewData['isCartItemsViaAjaxLoadEnabled'] = $this->getFactory()->getConfig()->isCartCartItemsViaAjaxLoadEnabled();

        return $this->view(
            $viewData,
            $this->getFactory()->getCartPageWidgetPlugins(),
            '@CartPage/views/cart/cart.twig'
        );
    }

    /**
     * @param array $selectedAttributes
     *
     * @return array
     */
    protected function executeIndexAction(array $selectedAttributes = []): array
    {
        $cartPageViewArgumentsTransfer = new CartPageViewArgumentsTransfer();
        $cartPageViewArgumentsTransfer->setLocale($this->getLocale())
            ->setSelectedAttributes($selectedAttributes)
            ->setIsQuoteValidationEnabled($this->getFactory()->getConfig()->isQuoteValidationEnabled());

        $viewData = $this->getFactory()->createCartPageView()->getViewData($cartPageViewArgumentsTransfer);

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $viewData;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function getCartItemsAjaxAction(Request $request): View
    {
        if (!$this->getFactory()->getConfig()->isCartCartItemsViaAjaxLoadEnabled()) {
            throw new NotFoundHttpException();
        }

        return $this->executeGetCartItemsAjaxAction();
    }

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    protected function executeGetCartItemsAjaxAction(): View
    {
        $cartPageViewArgumentsTransfer = new CartPageViewArgumentsTransfer();
        $cartPageViewArgumentsTransfer->setLocale($this->getLocale())
            ->setSelectedAttributes([])
            ->setIsQuoteValidationEnabled(false);

        $viewData = $this->getFactory()->createCartPageView()->getViewData($cartPageViewArgumentsTransfer);

        return $this->view($viewData, [], '@CartPage/views/ajax-cart-items/ajax-cart-items.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request, $sku)
    {
        $form = $this->getFactory()->createCartPageFormFactory()->getAddToCartForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $quantity = $request->get('quantity', 1);

        if (!$this->canAddCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setSku($sku)
            ->setQuantity($quantity);

        $this->addProductOptions($request->get('product-option', []), $itemTransfer);

        $itemTransfer = $this->executePreAddToCartPlugins($itemTransfer, $request->request->all());

        $this->getFactory()
            ->getCartClient()
            ->addItem($itemTransfer, $request->request->all());

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function quickAddAction(Request $request, string $sku): RedirectResponse
    {
        $quantity = $request->get('quantity', 1);

        if (!$this->canAddCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        return $this->executeQuickAddAction($sku, $quantity);
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeQuickAddAction(string $sku, int $quantity): RedirectResponse
    {
        $itemTransfer = (new ItemTransfer())
            ->setSku($sku)
            ->setQuantity($quantity)
            ->addNormalizableField(static::FIELD_QUANTITY_TO_NORMALIZE)
            ->setGroupKeyPrefix(uniqid('', true));

        $this->getFactory()
            ->getCartClient()
            ->addItem($itemTransfer);

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction(Request $request, $sku)
    {
        $groupKey = $request->get('groupKey', null);

        if (!$this->canRemoveCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $form = $this->getFactory()->createCartPageFormFactory()->getRemoveForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $this->getFactory()
            ->getCartClient()
            ->removeItem($sku, $groupKey);

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeAction(Request $request, $sku)
    {
        $quantity = $request->get('quantity', 1);

        if (!$this->canChangeCartItem($quantity)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $form = $this->getFactory()->createCartPageFormFactory()->getCartChangeQuantityForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $this->getFactory()
            ->getCartClient()
            ->changeItemQuantity($sku, $request->get('groupKey'), $quantity);

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addItemsAction(Request $request)
    {
        if (!$this->canAddCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $form = $this->getFactory()->createCartPageFormFactory()->getAddItemsForm()->handleRequest($request);

        if (!$form->isSubmitted() || !$form->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $items = (array)$request->request->get(self::PARAM_ITEMS);
        $itemTransfers = $this->mapItems($items);

        $this->getFactory()
            ->getCartClient()
            ->addItems($itemTransfers, $request->request->all());

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, $sku)
    {
        $quantity = $request->get('quantity', 1);

        if (!$this->canChangeCartItem($quantity)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        $isItemReplacedInCart = $this->getFactory()
            ->createCartItemsAttributeProvider()
            ->tryToReplaceItem(
                $sku,
                $quantity,
                array_replace($request->get('selectedAttributes', []), $request->get('preselectedAttributes', [])),
                $quoteTransfer->getItems(),
                $request->get('groupKey'),
                $request->get('product-option', []),
                $this->getLocale()
            );

        if ($isItemReplacedInCart) {
            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $this->addInfoMessage('cart.item_attributes_needed');

        return $this->redirectResponseInternal(
            CartPageRouteProviderPlugin::ROUTE_NAME_CART,
            $this->getFactory()
                ->createCartItemsAttributeProvider()
                ->formatUpdateActionResponse($sku, $request->get('selectedAttributes', []))
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function addAjaxAction(Request $request): JsonResponse
    {
        $response = $this->executeAddAjaxAction($request);

        return $this->jsonResponse($response);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeAddAjaxAction(Request $request): array
    {
        $csrfToken = $this->createCsrfToken(
            static::CSRF_TOKEN_ID,
            $request->get(static::REQUEST_PARAMETER_TOKEN)
        );

        if (!$this->getFactory()->getCsrfTokenManager()->isTokenValid($csrfToken)) {
            return $this->createAjaxErrorResponse(
                Response::HTTP_BAD_REQUEST,
                [static::GLOSSARY_KEY_ERROR_MESSAGE_UNEXPECTED_ERROR]
            );
        }

        if (!$this->canAddCartItem()) {
            return $this->createAjaxErrorResponse(
                Response::HTTP_FORBIDDEN,
                [static::MESSAGE_PERMISSION_FAILED]
            );
        }

        $sku = $request->get(static::REQUEST_PARAMETER_SKU);
        $quantity = $request->get(static::REQUEST_PARAMETER_QUANTITY, 1);

        $itemTransfer = (new ItemTransfer())
            ->setSku($sku)
            ->setQuantity($quantity);

        $itemTransfer = $this->executePreAddToCartPlugins($itemTransfer, $request->request->all());

        $this->getFactory()
            ->getCartClient()
            ->addItem($itemTransfer, $request->request->all());

        $messageTransfers = $this->getFactory()
            ->getZedRequestClient()
            ->getLastResponseErrorMessages();

        $cartQuantity = $this->getFactory()
            ->getCartClient()
            ->getItemCount();

        if ($messageTransfers) {
            $this->addErrorMessages($messageTransfers);
            $flashMessageListHtml = $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent();

            return [
                static::KEY_CODE => Response::HTTP_BAD_REQUEST,
                static::KEY_MESSAGES => $flashMessageListHtml,
            ];
        }

        $this->addSuccessMessages(
            $this->getFactory()->getZedRequestClient()->getLastResponseSuccessMessages()
        );

        return [
            static::KEY_CODE => Response::HTTP_OK,
            static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            static::REQUEST_PARAMETER_QUANTITY => $cartQuantity,
        ];
    }

    /**
     * @param string $tokenId
     * @param string $value
     *
     * @return \Symfony\Component\Security\Csrf\CsrfToken
     */
    protected function createCsrfToken(string $tokenId, string $value): CsrfToken
    {
        return new CsrfToken($tokenId, $value);
    }

    /**
     * @param array $items
     *
     * @return \Generated\Shared\Transfer\ItemTransfer[]
     */
    protected function mapItems(array $items)
    {
        $itemTransfers = [];

        foreach ($items as $item) {
            $itemTransfer = new ItemTransfer();
            $itemTransfer->fromArray($item, true);
            $itemTransfers[] = $itemTransfer;
        }

        return $itemTransfers;
    }

    /**
     * @param array $optionValueUsageIds
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return void
     */
    protected function addProductOptions(array $optionValueUsageIds, ItemTransfer $itemTransfer)
    {
        foreach ($optionValueUsageIds as $idOptionValueUsage) {
            if (!$idOptionValueUsage) {
                continue;
            }

            $productOptionTransfer = new ProductOptionTransfer();
            $productOptionTransfer->setIdProductOptionValue($idOptionValueUsage);

            $itemTransfer->addProductOption($productOptionTransfer);
        }
    }

    /**
     * @return bool
     */
    protected function canAddCartItem(): bool
    {
        return $this->canPerformCartItemAction(AddCartItemPermissionPlugin::KEY);
    }

    /**
     * @param int|null $itemQuantity
     *
     * @return bool
     */
    protected function canChangeCartItem(?int $itemQuantity = null): bool
    {
        if ($itemQuantity === 0) {
            return $this->canRemoveCartItem();
        }

        return $this->canPerformCartItemAction(ChangeCartItemPermissionPlugin::KEY);
    }

    /**
     * @return bool
     */
    protected function canRemoveCartItem(): bool
    {
        return $this->canPerformCartItemAction(RemoveCartItemPermissionPlugin::KEY);
    }

    /**
     * @param string $permissionPluginKey
     *
     * @return bool
     */
    protected function canPerformCartItemAction(string $permissionPluginKey): bool
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        if ($quoteTransfer->getCustomer() === null) {
            return true;
        }

        if ($quoteTransfer->getCustomer()->getCompanyUserTransfer() === null) {
            return true;
        }

        if ($this->can($permissionPluginKey)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function executePreAddToCartPlugins(ItemTransfer $itemTransfer, array $params): ItemTransfer
    {
        foreach ($this->getFactory()->getPreAddToCartPlugins() as $preAddToCartPlugin) {
            $itemTransfer = $preAddToCartPlugin->preAddToCart($itemTransfer, $params);
        }

        return $itemTransfer;
    }

    /**
     * @param int $code
     * @param string[] $messages
     *
     * @return array
     */
    protected function createAjaxErrorResponse(int $code, array $messages): array
    {
        foreach ($messages as $message) {
            $this->addErrorMessage($message);
        }

        $flashMessageListHtml = $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent();

        return [
            static::KEY_CODE => $code,
            static::KEY_MESSAGES => $flashMessageListHtml,
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[] $messageTransfers
     *
     * @return void
     */
    protected function addErrorMessages(array $messageTransfers): void
    {
        foreach ($messageTransfers as $messageTransfer) {
            $this->addErrorMessage($messageTransfer->getValue());
        }
    }

    /**
     * @param \Generated\Shared\Transfer\MessageTransfer[] $messageTransfers
     *
     * @return void
     */
    protected function addSuccessMessages(array $messageTransfers): void
    {
        foreach ($messageTransfers as $messageTransfer) {
            $this->addSuccessMessage($messageTransfer->getValue());
        }
    }
}
