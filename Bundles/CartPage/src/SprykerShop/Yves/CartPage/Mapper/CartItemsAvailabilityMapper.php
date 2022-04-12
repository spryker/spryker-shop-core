<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage\Mapper;

use ArrayObject;
use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\StorageAvailabilityTransfer;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityStorageClientInterface;

class CartItemsAvailabilityMapper implements CartItemsMapperInterface
{
    /**
     * @var string
     */
    public const CONCRETE_PRODUCT_AVAILABLE_ITEMS = 'concrete_product_available_items';

    /**
     * @var \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityStorageClientInterface
     */
    protected $productAvailabilityClient;

    /**
     * @param \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityStorageClientInterface $productAvailabilityClient
     */
    public function __construct(CartPageToAvailabilityStorageClientInterface $productAvailabilityClient)
    {
        $this->productAvailabilityClient = $productAvailabilityClient;
    }

    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ItemTransfer> $items
     * @param string $localeName
     *
     * @return array
     */
    public function buildMap(ArrayObject $items, $localeName)
    {
        $availabilityMap = [];
        foreach ($items as $item) {
            $availabilityMap = array_replace($availabilityMap, $this->getAvailability($item));
        }

        return $availabilityMap;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $item
     *
     * @return array
     */
    protected function getAvailability(ItemTransfer $item)
    {
        $availabilityBySku = [];

        $availability = $this->productAvailabilityClient->getProductAvailabilityByIdProductAbstract($item->getIdProductAbstract())->toArray();

        foreach ($availability[static::CONCRETE_PRODUCT_AVAILABLE_ITEMS] as $sku => $itemAvailable) {
            $availabilityBySku[$sku][StorageAvailabilityTransfer::CONCRETE_PRODUCT_AVAILABLE_ITEMS] = $itemAvailable;
        }

        return $availabilityBySku;
    }
}
