<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityRequestTransfer;
use Generated\Shared\Transfer\ProductOptionTransfer;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface;
use Symfony\Component\HttpFoundation\Request;

class CartOperationHandler extends BaseHandler implements CartOperationInterface
{
    const URL_PARAM_ID_DISCOUNT_PROMOTION = 'idDiscountPromotion';
    const TRANSLATION_KEY_QUANTITY_ADJUSTED = 'cart.quantity.adjusted';

    /**
     * @var \Spryker\Client\Cart\CartPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityClientInterface
     */
    protected $availabilityClient;

    /**
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface $cartClient
     * @param string $locale
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityClientInterface $availabilityClient
     */
    public function __construct(
        CartPageToCartClientInterface $cartClient,
        $locale,
        FlashMessengerInterface $flashMessenger,
        Request $request,
        CartPageToAvailabilityClientInterface $availabilityClient
    ) {
        parent::__construct($flashMessenger);
        $this->cartClient = $cartClient;
        $this->locale = $locale;
        $this->request = $request;
        $this->availabilityClient = $availabilityClient;
        $this->flashMessenger = $flashMessenger;
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
        $quantity = $this->normalizeQuantity($quantity);
        $quantity = $this->adjustQuantityBasedOnProductAvailability($sku, $quantity);

        $itemTransfer = new ItemTransfer();
        $itemTransfer->setSku($sku);
        $itemTransfer->setQuantity($quantity);
        $itemTransfer->setIdDiscountPromotion($this->getIdDiscountPromotion());

        $this->addProductOptions($optionValueUsageIds, $itemTransfer);

        $quoteTransfer = $this->cartClient->addItem($itemTransfer);
        $this->cartClient->storeQuote($quoteTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer[] $itemTransfers
     *
     * @return void
     */
    public function addItems(array $itemTransfers)
    {
        $quoteTransfer = $this->cartClient->addItems($itemTransfers);
        $this->cartClient->storeQuote($quoteTransfer);
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function remove($sku, $groupKey = null)
    {
        $quoteTransfer = $this->cartClient->removeItem($sku, $groupKey);
        $this->cartClient->storeQuote($quoteTransfer);
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function increase($sku, $groupKey = null)
    {
        $quoteTransfer = $this->cartClient->increaseItemQuantity($sku, $groupKey);
        $this->cartClient->storeQuote($quoteTransfer);
    }

    /**
     * @param string $sku
     * @param string|null $groupKey
     *
     * @return void
     */
    public function decrease($sku, $groupKey = null)
    {
        $quoteTransfer = $this->cartClient->decreaseItemQuantity($sku, $groupKey);
        $this->cartClient->storeQuote($quoteTransfer);
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
        $quoteTransfer = $this->cartClient->changeItemQuantity($sku, $groupKey, $quantity);
        $this->cartClient->storeQuote($quoteTransfer);
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
     * @return int
     */
    protected function getIdDiscountPromotion()
    {
        return (int)$this->request->request->get(static::URL_PARAM_ID_DISCOUNT_PROMOTION);
    }

    /**
     * @param int $quantity
     *
     * @return int
     */
    protected function normalizeQuantity($quantity)
    {
        if (!$quantity || $quantity <= 0) {
            $quantity = 1;
        }
        return $quantity;
    }

    /**
     * @param string $sku
     * @param int $quantity
     *
     * @return int
     */
    protected function adjustQuantityBasedOnProductAvailability($sku, $quantity)
    {
        $productAvailabilityRequestTransfer = (new ProductConcreteAvailabilityRequestTransfer())
            ->setSku($sku);

        $productConcreteAvailabilityTransfer = $this->availabilityClient
            ->findProductConcreteAvailability($productAvailabilityRequestTransfer);

        if ($productConcreteAvailabilityTransfer->getIsNeverOutOfStock()) {
            return $quantity;
        }

        if ($quantity > $productConcreteAvailabilityTransfer->getAvailability()) {
            $this->flashMessenger->addInfoMessage(static::TRANSLATION_KEY_QUANTITY_ADJUSTED);
            return $productConcreteAvailabilityTransfer->getAvailability();
        }

        return $quantity;
    }
}
