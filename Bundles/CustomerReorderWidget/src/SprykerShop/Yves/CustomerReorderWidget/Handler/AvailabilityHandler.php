<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget\Handler;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface;

/**
 * @todo discuss
 * this is hard dependency on availability
 */
class AvailabilityHandler
{
    /**
     * @var \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @param \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface $productStorageClient
     */
    public function __construct(
        CustomerReorderWidgetToProductStorageClientInterface $productStorageClient
    ) {
        $this->productStorageClient = $productStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param string $locale
     *
     * @return bool
     */
    public function getAvailabilityForItemTransfer(ItemTransfer $itemTransfer, string $locale): bool
    {
        $productConcreteStorageData = $this->productStorageClient
            ->getProductConcreteStorageData($itemTransfer->getId(), $locale);

        $productViewTransfer = new ProductViewTransfer();
        $productViewTransfer->fromArray($productConcreteStorageData, true);

        if ($productViewTransfer->getPrice() === null) {
            return false;
        }

        return $productViewTransfer->getAvailable();
    }
}
