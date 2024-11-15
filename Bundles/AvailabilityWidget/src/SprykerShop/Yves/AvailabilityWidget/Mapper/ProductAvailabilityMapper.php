<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AvailabilityWidget\Mapper;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer;
use SprykerShop\Yves\AvailabilityWidget\Dependency\Client\AvailabilityWidgetToAvailabilityStorageClientInterface;

class ProductAvailabilityMapper implements ProductAvailabilityMapperInterface
{
    /**
     * @uses \SprykerShop\Yves\CartReorderPage\Widget\CartReorderItemCheckboxWidget::PARAMETER_ATTRIBUTE_DISABLED
     *
     * @var string
     */
    protected const PARAMETER_ATTRIBUTE_DISABLED = 'attributeDisabled';

    /**
     * @var \SprykerShop\Yves\AvailabilityWidget\Dependency\Client\AvailabilityWidgetToAvailabilityStorageClientInterface
     */
    protected AvailabilityWidgetToAvailabilityStorageClientInterface $availabilityStorageClient;

    /**
     * @param \SprykerShop\Yves\AvailabilityWidget\Dependency\Client\AvailabilityWidgetToAvailabilityStorageClientInterface $availabilityStorageClient
     */
    public function __construct(AvailabilityWidgetToAvailabilityStorageClientInterface $availabilityStorageClient)
    {
        $this->availabilityStorageClient = $availabilityStorageClient;
    }

    /**
     * @param array<string, mixed> $attributes
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return array<string, mixed>
     */
    public function mapProductAvailabilityAttributes(array $attributes, ItemTransfer $itemTransfer): array
    {
        $attributes[static::PARAMETER_ATTRIBUTE_DISABLED] = !$this->isItemAvailable($itemTransfer);

        return $attributes;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function isItemAvailable(ItemTransfer $itemTransfer): bool
    {
        $productAbstractAvailabilityTransfer = $this->availabilityStorageClient
            ->findProductAbstractAvailability($itemTransfer->getIdProductAbstractOrFail());

        if (!$productAbstractAvailabilityTransfer) {
            return false;
        }

        foreach ($productAbstractAvailabilityTransfer->getProductConcreteAvailabilities() as $productConcreteAvailabilityTransfer) {
            if ($this->checkItemAvailability($productConcreteAvailabilityTransfer, $itemTransfer)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return bool
     */
    protected function checkItemAvailability(
        ProductConcreteAvailabilityTransfer $productConcreteAvailabilityTransfer,
        ItemTransfer $itemTransfer
    ): bool {
        if ($productConcreteAvailabilityTransfer->getSkuOrFail() === $itemTransfer->getSkuOrFail()) {
            if ($productConcreteAvailabilityTransfer->getIsNeverOutOfStockOrFail() || $itemTransfer->getQuantityOrFail() <= $productConcreteAvailabilityTransfer->getAvailabilityOrFail()->toFloat()) {
                return true;
            }
        }

        return false;
    }
}
