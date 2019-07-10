<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Controller;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
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

    protected const FIELD_QUANTITY_TO_NORMALIZE = 'quantity';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(Request $request)
    {
        $viewData = $this->executeIndexAction($this->getSelectedAttributes($request));

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
        $validateQuoteResponseTransfer = $this->getFactory()
            ->getCartClient()
            ->validateQuote();

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        $quoteTransfer = $validateQuoteResponseTransfer->getQuoteTransfer();

        $cartItems = $this->getFactory()
            ->createCartItemReader()
            ->getCartItems($quoteTransfer);

        $itemAttributesBySku = $this->getFactory()
            ->createCartItemsAttributeProvider()
            ->getItemsAttributes($quoteTransfer, $this->getLocale(), $selectedAttributes);

        $quoteClient = $this->getFactory()->getQuoteClient();

        return [
            'cart' => $quoteTransfer,
            'isQuoteEditable' => $quoteClient->isQuoteEditable($quoteTransfer),
            'isQuoteLocked' => $quoteClient->isQuoteLocked($quoteTransfer),
            'cartItems' => $cartItems,
            'attributes' => $itemAttributesBySku,
            'isQuoteValid' => $validateQuoteResponseTransfer->getIsSuccessful(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     * @param int $quantity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addAction(Request $request, $sku, $quantity = 1)
    {
        if (!$this->canAddCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        $itemTransfer = new ItemTransfer();
        $itemTransfer
            ->setSku($sku)
            ->setQuantity($quantity);

        $this->addProductOptions($this->getProductOptions($request), $itemTransfer);

        $this->getFactory()
            ->getCartClient()
            ->addItem($itemTransfer, $request->request->all());

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function quickAddAction(string $sku, int $quantity): RedirectResponse
    {
        if (!$this->canAddCartItem()) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
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

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
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
            ->addResponseMessagesToMessenger();

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     * @param int $quantity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeAction(Request $request, $sku, $quantity = 1)
    {
        if (!$this->canChangeCartItem($quantity)) {
            $this->addErrorMessage(static::MESSAGE_PERMISSION_FAILED);

            return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
        }

        $this->getFactory()
            ->getCartClient()
            ->changeItemQuantity($sku, $this->getGroupKey($request), $quantity);

        $this->getFactory()
            ->getZedRequestClient()
            ->addResponseMessagesToMessenger();

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
            ->addResponseMessagesToMessenger();

        return $this->redirectResponseInternal(CartControllerProvider::ROUTE_CART);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param string $sku
     * @param int $quantity
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request, $sku, $quantity = 1)
    {
        if (!$this->canChangeCartItem($quantity)) {
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
                array_replace($this->getSelectedAttributes($request), $this->getPreSelectedAttributes($request)),
                $quoteTransfer->getItems(),
                $this->getGroupKey($request),
                $this->getProductOptions($request),
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
                ->formatUpdateActionResponse($sku, $this->getSelectedAttributes($request))
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    protected function getGroupKey(Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('groupKey');
        }

        return $request->query->get('groupKey');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return int
     */
    protected function getQuantity(Request $request)
    {
        if ($request->isMethod('POST')) {
            return $request->request->getInt('quantity', 1);
        }

        return $request->query->getInt('quantity', 1);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getSelectedAttributes(Request $request): array
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('selectedAttributes', []);
        }

        return $request->query->get('selectedAttributes', []);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getPreSelectedAttributes(Request $request): array
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('preselectedAttributes', []);
        }

        return $request->query->get('preselectedAttributes', []);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getProductOptions(Request $request): array
    {
        if ($request->isMethod('POST')) {
            return $request->request->get('product-option', []);
        }

        return $request->query->get('product-option', []);
    }
}
