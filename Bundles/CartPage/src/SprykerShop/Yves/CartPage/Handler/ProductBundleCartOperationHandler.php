<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Spryker\Yves\Kernel\PermissionAwareTrait;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Shared\CartPage\Plugin\AddCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\ChangeCartItemPermissionPlugin;
use SprykerShop\Shared\CartPage\Plugin\RemoveCartItemPermissionPlugin;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface;

// TODO: this needs to be moved from this module
class ProductBundleCartOperationHandler extends BaseHandler implements CartOperationInterface
{
    use PermissionAwareTrait;

    public const GLOSSARY_KEY_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var \SprykerShop\Yves\CartPage\Handler\CartOperationInterface
     */
    protected $cartOperationHandler;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @param \SprykerShop\Yves\CartPage\Handler\CartOperationInterface $cartOperationHandler
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface $cartClient
     * @param string $locale
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     */
    public function __construct(
        CartOperationInterface $cartOperationHandler,
        CartPageToCartClientInterface $cartClient,
        $locale,
        FlashMessengerInterface $flashMessenger
    ) {
        $this->cartOperationHandler = $cartOperationHandler;

        parent::__construct($flashMessenger);
        $this->cartClient = $cartClient;
        $this->locale = $locale;
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param array $optionValueUsageIds
     *
     * @return void
     */
    public function add($sku, $quantity, array $optionValueUsageIds = [])
    {
        if (!$this->can(AddCartItemPermissionPlugin::KEY)) {
            $this->flashMessenger->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);
            return;
        }

        $this->cartOperationHandler->add($sku, $quantity, $optionValueUsageIds);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function addItems(array $itemTransfers)
    {
        if (!$this->can(AddCartItemPermissionPlugin::KEY)) {
            $this->flashMessenger->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);
            return;
        }

        $this->cartOperationHandler->addItems($itemTransfers);
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function remove($sku, $groupKey = null)
    {
        if (!$this->can(RemoveCartItemPermissionPlugin::KEY)) {
            $this->flashMessenger->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);
            return;
        }

        $bundledItemsToRemove = $this->getBundledItems($groupKey);
        if (count($bundledItemsToRemove) > 0) {
            $this->cartClient->removeItems($bundledItemsToRemove);

            return;
        }

        $this->cartClient->removeItem($sku, $groupKey);
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function increase($sku, $groupKey = null)
    {
        $bundledProductTotalQuantity = $this->getBundledProductTotalQuantity($sku);
        $this->cartOperationHandler->changeQuantity($sku, $bundledProductTotalQuantity + 1, $groupKey);
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function decrease($sku, $groupKey = null)
    {
        $bundledProductTotalQuantity = $this->getBundledProductTotalQuantity($sku);
        $this->cartOperationHandler->changeQuantity($sku, $bundledProductTotalQuantity - 1, $groupKey);
    }

    /**
     * @param string $sku
     * @param int $quantity
     * @param string|null $groupKey
     *
     * @return void
     */
    public function changeQuantity($sku, $quantity, $groupKey = null)
    {
        if (!$this->can(ChangeCartItemPermissionPlugin::KEY)) {
            $this->flashMessenger->addErrorMessage(static::GLOSSARY_KEY_PERMISSION_FAILED);
            return;
        }

        $bundledProductTotalQuantity = $this->getBundledProductTotalQuantity($groupKey);

        if ($bundledProductTotalQuantity > 0) {
            $this->handleBundleProductQuantity($bundledProductTotalQuantity, $sku, $quantity, $groupKey);

            return;
        }

        $this->cartClient->changeItemQuantity($sku, $groupKey, $quantity);
    }

    /**
     * @param int $bundledProductTotalQuantity
     * @param string $sku
     * @param int $quantity
     * @param string $groupKey
     *
     * @return void
     */
    protected function handleBundleProductQuantity($bundledProductTotalQuantity, $sku, $quantity, $groupKey)
    {
        $delta = abs($bundledProductTotalQuantity - $quantity);

        if ($delta === 0) {
            return;
        }

        if ($bundledProductTotalQuantity > $quantity) {
            $bundledItemsToRemove = $this->getBundledItems($groupKey, $delta);
            $this->cartClient->removeItems($bundledItemsToRemove);

            return;
        }

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($sku);
        $itemTransfer->setQuantity($delta);
        $itemTransfer->setProductOptions($this->getBundleProductOptions($groupKey));

        $this->cartClient->addItem($itemTransfer);
    }

    /**
     * @param string $groupKey
     *
     * @return \ArrayObject|\Generated\Shared\Transfer\ProductOptionTransfer[]
     */
    protected function getBundleProductOptions($groupKey)
    {
        $quoteTransfer = $this->cartClient->getQuote();
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }
            return $bundleItemTransfer->getProductOptions();
        }

        return new ArrayObject();
    }

    /**
     * @param string $groupKey
     * @param int $numberOfBundlesToRemove
     *
     * @return \ArrayObject
     */
    protected function getBundledItems($groupKey, $numberOfBundlesToRemove = 0)
    {
        if (!$numberOfBundlesToRemove) {
            $numberOfBundlesToRemove = $this->getBundledProductTotalQuantity($groupKey);
        }

        $quoteTransfer = $this->cartClient->getQuote();
        $bundledItems = new ArrayObject();
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($numberOfBundlesToRemove == 0) {
                return $bundledItems;
            }

            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }

            foreach ($quoteTransfer->getItems() as $itemTransfer) {
                if ($itemTransfer->getRelatedBundleItemIdentifier() !== $bundleItemTransfer->getBundleItemIdentifier()) {
                    continue;
                }
                $bundledItems->append($itemTransfer);
            }
            $numberOfBundlesToRemove--;
        }

        return $bundledItems;
    }

    /**
     * @param string $groupKey
     *
     * @return int
     */
    protected function getBundledProductTotalQuantity($groupKey)
    {
        $quoteTransfer = $this->cartClient->getQuote();

        $bundleItemQuantity = 0;
        foreach ($quoteTransfer->getBundleItems() as $bundleItemTransfer) {
            if ($bundleItemTransfer->getGroupKey() !== $groupKey) {
                continue;
            }
            $bundleItemQuantity += $bundleItemTransfer->getQuantity();
        }

        return $bundleItemQuantity;
    }
}
