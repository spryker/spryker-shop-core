<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Controller;

use ArrayObject;
use Generated\Shared\Transfer\CartChangeTransfer;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Generated\Shared\Transfer\QuoteResponseTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use SprykerShop\Shared\CartPage\Plugin\AddCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\ChangeCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin;
use SprykerShop\Yves\CartPage\Plugin\Provider\CartControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 */
class CartController extends AbstractController
{
    use PermissionAwareTrait;

    public const MESSAGE_PERMISSION_FAILED = 'global.permission.failed';

    public const PARAM_ITEMS = 'items';

    /**
     * @param array|null $selectedAttributes
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(?array $selectedAttributes = null)
    {
        $viewData = $this->executeIndexAction($selectedAttributes);

        return $this->view(
            $viewData,
            $this->getFactory()->getCartPageWidgetPlugins(),
            '@CartPage/views/cart/cart.twig'
        );
    }

    /**
     * @param array|null $selectedAttributes
     *
     * @return array
     */
    protected function executeIndexAction(?array $selectedAttributes): array
    {
        $validateQuoteResponseTransfer = $this->getFactory()
            ->getCartClient()
            ->validateQuote();

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        $quoteTransfer = $validateQuoteResponseTransfer->getQuoteTransfer();

        $cartItems = $this->getFactory()
            ->createCartItemReader()
            ->getCartItems($quoteTransfer);

        $itemAttributesBySku = $this->getFactory()
            ->createCartItemsAttributeProvider()
            ->getItemsAttributes($quoteTransfer, $this->getLocale(), $selectedAttributes);

        return [
            'cart' => $quoteTransfer,
            'cartItems' => $cartItems,
            'attributes' => $itemAttributesBySku,
            'isQuoteValid' => $validateQuoteResponseTransfer->getIsSuccessful(),
        ];
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param array $optionValueIds
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction($sku, $quantity, array $optionValueIds, Request $request)
    {
        if (!$this->canAddCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setSku($sku)
            ->setQuantity($quantity);

        $this->addProductOptions($optionValueIds, $itemTransfer);

        $this->getFactory()
            ->getCartClient()
            ->addItem($itemTransfer, $request->request->all());

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function quickAddAction($sku, $quantity, Request $request): RedirectResponse
    {
        if (!$this->canAddCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        return $this->executeQuickAddAction($sku, $quantity, $request);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function executeQuickAddAction($sku, $quantity, Request $request): RedirectResponse
    {
        $itemTransfer = $this->buildCartChangeItemTransfer($sku, $quantity);

        $this->getFactory()
            ->getCartClient()
            ->addItem($itemTransfer, $request->request->all());

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        $quoteResponseTransfer = $this->getFactory()
            ->getZedRequestClient()
            ->findLastResponseTransfer();

        if ($quoteResponseTransfer instanceof QuoteResponseTransfer
            && $quoteResponseTransfer->getIsSuccessful()
            && $itemTransfer->getNotificationMessages()->count() > 0
        ) {
            $this->addFlashNotificationMessages($itemTransfer->getNotificationMessages());
        }

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function buildCartChangeItemTransfer(string $sku, int $quantity): ItemTransfer
    {
        $productConcreteTransfer = $this->getFactory()
            ->createProductConcreteReader()
            ->getProductConcreteTransferBySku($sku);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($sku)
            ->setQuantity($quantity)
            ->setProductConcrete($productConcreteTransfer);

        $cartChangeTransfer = new CartChangeTransfer();
        $cartChangeTransfer->addItem($itemTransfer);

        $cartChangeTransfer = $this->getFactory()
            ->getCartClient()
            ->expandCartChangeTransfer($cartChangeTransfer);

        return $cartChangeTransfer->getItems()[0];
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeAction($sku, $groupKey = null)
    {
        if (!$this->canRemoveCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        $this->getFactory()
            ->getCartClient()
            ->removeItem($sku, $groupKey);

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param string|null $groupKey
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeAction($sku, $quantity, $groupKey = null)
    {
        if (!$this->canChangeCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        $this->getFactory()
            ->getCartClient()
            ->changeItemQuantity($sku, $groupKey, $quantity);

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
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

            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        $items = (array)$request->request->get(self::PARAM_ITEMS);
        $itemTransfers = $this->mapItems($items);

        $this->getFactory()
            ->getCartClient()
            ->addItems($itemTransfers, $request->request->all());

        $this->getFactory()
            ->getZedRequestClient()
            ->addFlashMessagesFromLastZedRequest();

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param array $selectedAttributes
     * @param array $preselectedAttributes
     * @param string|null $groupKey
     * @param array $optionValueIds
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction($sku, $quantity, array $selectedAttributes, array $preselectedAttributes, $groupKey = null, array $optionValueIds = [])
    {
        if (!$this->canChangeCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        $isItemReplacedInCart = $this->getFactory()
            ->createCartItemsAttributeProvider()
            ->tryToReplaceItem(
                $sku,
                $quantity,
                array_replace($selectedAttributes, $preselectedAttributes),
                $quoteTransfer->getItems(),
                $groupKey,
                $optionValueIds,
                $this->getLocale()
            );

        if ($isItemReplacedInCart) {
            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        $this->addInfoMessage('cart.item_attributes_needed');

        return $this->redirectResponseInternal(
            CartControllerProvider::ROUTE_CART,
            $this->getFactory()
                ->createCartItemsAttributeProvider()
                ->formatUpdateActionResponse($sku, $selectedAttributes)
        );
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
     * @return bool
     */
    protected function canChangeCartItem(): bool
    {
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
     * @param Generated\Shared\Transfer\MessageTransfer[] $notificationMessages
     *
     * @return void
     */
    protected function addFlashNotificationMessages(ArrayObject $notificationMessages): void
    {
        $glossaryClient = $this->getFactory()->getGlossaryClient();

        foreach ($notificationMessages as $notificationMessage) {
            $errorMessageText = $glossaryClient->translate(
                $notificationMessage->getValue(),
                $this->getLocale(),
                $notificationMessage->getParameters()
            );

            $this->addInfoMessage($errorMessageText);
        }
    }
}
