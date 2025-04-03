<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Controller;

use Generated\Shared\Transfer\CartPageViewArgumentsTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\NumberFormatFilterTransfer;
use Generated\Shared\Transfer\NumberFormatIntRequestTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\CartPage\Plugin\Router\CartPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 * @method \SprykerShop\Yves\CartPage\CartPageConfig getConfig()
 */
class CartController extends AbstractCartController
{
    /**
     * @var string
     */
    public const MESSAGE_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var string
     */
    public const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';

    /**
     * @var string
     */
    public const PARAM_ITEMS = 'items';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_TOKEN = '_token';

    /**
     * @var string
     */
    protected const KEY_CODE = 'code';

    /**
     * @var string
     */
    protected const CSRF_TOKEN_ID = 'add-to-cart-ajax';

    /**
     * @var string
     */
    protected const MESSAGE_TYPE_ERROR = 'error';

    /**
     * @var string
     */
    protected const FLASH_MESSAGE_LIST_TEMPLATE_PATH = '@ShopUi/components/organisms/flash-message-list/flash-message-list.twig';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_ERROR_MESSAGE_UNEXPECTED_ERROR = 'cart_page.error_message.unexpected_error';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_QUANTITY_FORMATTED = 'quantityFormatted';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        $isCartItemsViaAjaxLoadEnabled = $this->getFactory()->getConfig()->isCartCartItemsViaAjaxLoadEnabled();

        $viewData = $this->executeIndexAction($request->get(static::REQUEST_PARAMETER_SELECTED_ATTRIBUTES, []), !$isCartItemsViaAjaxLoadEnabled);
        $viewData['isCartItemsViaAjaxLoadEnabled'] = $isCartItemsViaAjaxLoadEnabled;
        $viewData['isUpsellingProductsViaAjaxEnabled'] = $this->getFactory()->getConfig()->isLoadingUpsellingProductsViaAjaxEnabled();

        return $this->view(
            $viewData,
            $this->getFactory()->getCartPageWidgetPlugins(),
            '@CartPage/views/cart/cart.twig',
        );
    }

    /**
     * @param array $selectedAttributes
     * @param bool $withItems
     *
     * @return array<string, mixed>
     */
    protected function executeIndexAction(array $selectedAttributes = [], bool $withItems = true): array
    {
        $cartPageViewArgumentsTransfer = new CartPageViewArgumentsTransfer();
        $cartPageViewArgumentsTransfer->setLocale($this->getLocale())
            ->setSelectedAttributes($selectedAttributes)
            ->setWithItems($withItems);

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
    public function getUpsellingProductsWidgetAjaxAction(Request $request): View
    {
        if (!$this->getFactory()->getConfig()->isLoadingUpsellingProductsViaAjaxEnabled()) {
            throw new NotFoundHttpException();
        }

        return $this->executeGetUpsellingProductsWidgetAjaxAction();
    }

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    protected function executeGetUpsellingProductsWidgetAjaxAction(): View
    {
        $viewData = [
            'cart' => $this->getFactory()->getCartClient()->getQuote(),
        ];

        return $this->view($viewData, [], '@CartPage/views/ajax-upselling-widget/ajax-upselling-widget.twig');
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
            ->setWithItems(true);

        $viewData = $this->getFactory()->createCartPageView()->getAjaxCartItemsViewData($cartPageViewArgumentsTransfer);

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

        $this->addProductOptions($request->get(static::REQUEST_PARAMETER_PRODUCT_OPTION, []), $itemTransfer);

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

        return $this->executeQuickAddAction($request, $sku, $quantity);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     * @param int $quantity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeQuickAddAction(Request $request, string $sku, int $quantity): RedirectResponse
    {
        $itemTransfer = (new ItemTransfer())
            ->setSku($sku)
            ->setQuantity($quantity)
            ->addNormalizableField(static::FIELD_QUANTITY_TO_NORMALIZE)
            ->setGroupKeyPrefix(uniqid('', true));

        $itemTransfer = $this->executePreAddToCartPlugins($itemTransfer, $request->query->all());

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
        $groupKey = $request->get(static::REQUEST_PARAMETER_GROUP_KEY, null);

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
            ->changeItemQuantity($sku, $request->get(static::REQUEST_PARAMETER_GROUP_KEY), $quantity);

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

        $items = $request->request->all(static::PARAM_ITEMS);
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

        $isItemReplacedInCart = $this->replaceItem($sku, (int)$quantity, $request);

        if ($isItemReplacedInCart) {
            return $this->redirectResponseInternal(CartPageRouteProviderPlugin::ROUTE_NAME_CART);
        }

        $this->addInfoMessage(static::MESSAGE_ITEM_ATTRIBUTES_NEEDED);

        return $this->redirectResponseInternal(
            CartPageRouteProviderPlugin::ROUTE_NAME_CART,
            $this->getFactory()
                ->createCartItemsAttributeProvider()
                ->formatUpdateActionResponse($sku, $request->get(static::REQUEST_PARAMETER_SELECTED_ATTRIBUTES, [])),
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
            $request->get(static::REQUEST_PARAMETER_TOKEN),
        );

        if (!$this->getFactory()->getCsrfTokenManager()->isTokenValid($csrfToken)) {
            return $this->createAjaxErrorResponse(
                Response::HTTP_BAD_REQUEST,
                [static::GLOSSARY_KEY_ERROR_MESSAGE_UNEXPECTED_ERROR],
            );
        }

        if (!$this->canAddCartItem()) {
            return $this->createAjaxErrorResponse(
                Response::HTTP_FORBIDDEN,
                [static::MESSAGE_PERMISSION_FAILED],
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
            $this->getFactory()->getZedRequestClient()->getLastResponseSuccessMessages(),
        );

        return [
            static::KEY_CODE => Response::HTTP_OK,
            static::KEY_MESSAGES => $this->renderView(static::FLASH_MESSAGE_LIST_TEMPLATE_PATH)->getContent(),
            static::REQUEST_PARAMETER_QUANTITY => $cartQuantity,
            static::REQUEST_PARAMETER_QUANTITY_FORMATTED => $this->getFormattedCartQuantity($cartQuantity),
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
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
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
     * @param int $code
     * @param array<string> $messages
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
     * @param int $cartQuantity
     *
     * @return string
     */
    protected function getFormattedCartQuantity(int $cartQuantity): string
    {
        $numberFormatIntRequestTransfer = (new NumberFormatIntRequestTransfer())
            ->setNumber($cartQuantity)
            ->setNumberFormatFilter(
                (new NumberFormatFilterTransfer())->setLocale(
                    $this->getFactory()->getLocale(),
                ),
            );

        return $this->getFactory()
            ->getUtilNumberService()
            ->formatInt($numberFormatIntRequestTransfer);
    }
}
