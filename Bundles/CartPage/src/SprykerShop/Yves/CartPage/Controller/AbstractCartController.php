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
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageFactory getFactory()
 * @method \SprykerShop\Yves\CartPage\CartPageConfig getConfig()
 */
abstract class AbstractCartController extends AbstractController
{
    use PermissionAwareTrait;

    /**
     * @var string
     */
    protected const MESSAGE_ITEM_ATTRIBUTES_NEEDED = 'cart.item_attributes_needed';

    /**
     * @var string
     */
    protected const FIELD_QUANTITY_TO_NORMALIZE = 'quantity';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_SKU = 'sku';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_GROUP_KEY = 'groupKey';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_QUANTITY = 'quantity';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_SELECTED_ATTRIBUTES = 'selectedAttributes';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_PRE_SELECTED_ATTRIBUTES = 'preselectedAttributes';

    /**
     * @var string
     */
    protected const REQUEST_PARAMETER_PRODUCT_OPTION = 'product-option';

    /**
     * @var string
     */
    protected const KEY_MESSAGES = 'messages';

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
     * @param array<string, mixed> $params
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
     * @param array<int> $optionValueUsageIds
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
     * @param string $sku
     * @param int $quantity
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function replaceItem(string $sku, int $quantity, Request $request): bool
    {
        $quoteTransfer = $this->getFactory()
            ->getCartClient()
            ->getQuote();

        return $this->getFactory()
            ->createCartItemsAttributeProvider()
            ->tryToReplaceItem(
                $sku,
                $quantity,
                array_replace(
                    $request->get(static::REQUEST_PARAMETER_SELECTED_ATTRIBUTES, []),
                    $request->get(static::REQUEST_PARAMETER_PRE_SELECTED_ATTRIBUTES, []),
                ),
                $quoteTransfer->getItems(),
                $request->get(static::REQUEST_PARAMETER_GROUP_KEY),
                $request->get(static::REQUEST_PARAMETER_PRODUCT_OPTION, []),
                $this->getLocale(),
            );
    }

    /**
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $messageTransfers
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
     * @param array<\Generated\Shared\Transfer\MessageTransfer> $messageTransfers
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
