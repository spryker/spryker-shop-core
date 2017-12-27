<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CartPage\Handler;

use ArrayObject;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\StorageProductTransfer;
use Spryker\Shared\CartVariant\CartVariantConstants;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface;

class CartItemHandler extends BaseHandler implements CartItemHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\CartPage\Handler\CartOperationInterface
     */
    protected $cartOperationHandler;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface
     */
    protected $cartClient;

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface
     */
    protected $productClient;

    /**
     * @param \SprykerShop\Yves\CartPage\Handler\CartOperationInterface $cartOperationHandler
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface $cartClient
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface $productClient
     * @param \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface $flashMessenger
     */
    public function __construct(
        CartOperationInterface $cartOperationHandler,
        CartPageToCartClientInterface $cartClient,
        CartPageToProductStorageClientInterface $productClient,
        FlashMessengerInterface $flashMessenger
    ) {

        parent::__construct($flashMessenger);

        $this->cartOperationHandler = $cartOperationHandler;
        $this->cartClient = $cartClient;
        $this->productClient = $productClient;
    }

    /**
     * @param string $sku
     * @param array $selectedAttributes
     * @param \ArrayObject|\Generated\Shared\Transfer\StorageProductTransfer[] $items
     * @param string $localeName
     *
     * @return ProductViewTransfer
     */
    public function getProductViewTransfer($sku, array $selectedAttributes, ArrayObject $items, $localeName)
    {
        return $this->mapSelectedAttributesToStorageProduct($sku, $selectedAttributes, $items, $localeName);
    }

    /**
     * @param string $currentItemSku
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param int $quantity
     * @param string $groupKey
     * @param array $optionValueIds
     *
     * @return void
     */
    public function replaceCartItem(
        $currentItemSku,
        ProductViewTransfer $productViewTransfer,
        $quantity,
        $groupKey,
        array $optionValueIds
    ) {
        $newItemSku = $productViewTransfer->getSku();
        $this->cartOperationHandler->add($newItemSku, $quantity, $optionValueIds);
        $this->setFlashMessagesFromLastZedRequest($this->cartClient);

        if (count($this->cartClient->getZedStub()->getErrorMessages()) === 0) {
            $this->cartOperationHandler->remove($currentItemSku, $groupKey);
        }
    }

    /**
     * @param string $sku
     * @param array $selectedAttributes
     * @param \ArrayObject|\Generated\Shared\Transfer\StorageProductTransfer[] $items
     * @param $localeName
     *
     * @return ProductViewTransfer
     */
    public function mapSelectedAttributesToStorageProduct($sku, array $selectedAttributes, ArrayObject $items, $localeName)
    {
        foreach ($items as $item) {
            if ($item->getSku() === $sku) {
                return $this->getStorageProductForSelectedAttributes($selectedAttributes, $item, $localeName);
            }
        }

        return new ProductViewTransfer();
    }

    /**
     * @param array $selectedAttributes
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer
     */
    protected function getStorageProductForSelectedAttributes(array $selectedAttributes, $item, $localeName)
    {
        $productData = $this->productClient->getProductAbstractStorageData(
            $item->getIdProductAbstract(),
            $localeName
        );
        return $this->productClient->mapProductStorageData(
            $productData,
            $localeName,
            $selectedAttributes
        );
    }

    /**
     * @param string $sku
     * @param \ArrayObject|\Generated\Shared\Transfer\ItemTransfer[] $items
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    protected function findItemInCartBySku($sku, ArrayObject $items)
    {
        foreach ($items as $item) {
            if ($item->getSku() === $sku) {
                return $item;
            }
        }
    }

    /**
     * @param \ArrayObject|\Generated\Shared\Transfer\StorageProductTransfer[] $items
     * @param array $itemAttributesBySku
     * @param array|null $selectedAttributes
     * @param string $localeName
     *
     * @return array
     */
    public function narrowDownOptions(
        ArrayObject $items,
        array $itemAttributesBySku,
        array $selectedAttributes = null,
        $localeName
    ) {

        if (count($selectedAttributes) === 0) {
            return $itemAttributesBySku;
        }

        foreach ($selectedAttributes as $sku => $attributes) {
            $itemAttributesBySku = $this->setSelectedAttributesAsSelected($itemAttributesBySku, $attributes, $sku);

            $availableAttributes = $this->getAvailableAttributesForItem($items, $selectedAttributes, $sku, $localeName);

            $itemAttributesBySku = $this->removeAttributesThatAreNotAvailableForItem($itemAttributesBySku, $sku, $availableAttributes);
        }

        return $itemAttributesBySku;
    }

    /**
     * @param array $itemAttributesBySku
     * @param array $attributes
     * @param string $sku
     *
     * @return array
     */
    protected function setSelectedAttributesAsSelected(array $itemAttributesBySku, array $attributes, $sku)
    {
        foreach ($attributes as $key => $attribute) {
            unset($itemAttributesBySku[$sku][$key]);
            $itemAttributesBySku[$sku][$key][$attribute][CartVariantConstants::SELECTED] = true;
            $itemAttributesBySku[$sku][$key][$attribute][CartVariantConstants::AVAILABLE] = true;
        }
        return $itemAttributesBySku;
    }

    /**
     * @param \ArrayObject $items
     * @param array $itemAttributes
     * @param string $sku\
     * @param string $localeName
     *
     * @return array
     */
    protected function getAvailableAttributesForItem(ArrayObject $items, array $itemAttributes, $sku, $localeName)
    {
        $storageProductTransfer = $this->getProductViewTransfer($sku, $itemAttributes[$sku], $items, $localeName);
        $availableAttributes = $storageProductTransfer->getAvailableAttributes();

        return $availableAttributes;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @param array $itemAttributesBySku
     * @param string $sku
     * @param array $availableAttributes
     *
     * @return array
     */
    protected function removeAttributesThatAreNotAvailableForItem(array $itemAttributesBySku, $sku, array $availableAttributes)
    {
        foreach ($itemAttributesBySku[$sku] as $key => $attributes) {
            foreach ($attributes as $attributeName => $options) {
                if (array_key_exists($key, $availableAttributes)) {
                    if (in_array($attributeName, $availableAttributes[$key]) === false) {
                        unset($itemAttributesBySku[$sku][$key][$attributeName]);
                    }
                }
            }
        }

        return $itemAttributesBySku;
    }
}
