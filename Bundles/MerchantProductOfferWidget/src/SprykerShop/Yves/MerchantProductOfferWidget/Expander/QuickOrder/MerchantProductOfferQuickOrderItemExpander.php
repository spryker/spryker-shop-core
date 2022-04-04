<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Expander\QuickOrder;

use Generated\Shared\Transfer\ItemTransfer;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface;

class MerchantProductOfferQuickOrderItemExpander implements MerchantProductOfferQuickOrderItemExpanderInterface
{
 /**
  * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface
  */
    protected $productOfferStorageClient;

    /**
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface $productOfferStorageClient
     */
    public function __construct(MerchantProductOfferWidgetToProductOfferStorageClientInterface $productOfferStorageClient)
    {
        $this->productOfferStorageClient = $productOfferStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItem(ItemTransfer $itemTransfer): ItemTransfer
    {
        /** @var string $productOfferReference */
        $productOfferReference = $itemTransfer->getProductOfferReference();

        if (!$productOfferReference) {
            return $itemTransfer;
        }

        $productOfferStorageTransfer = $this->productOfferStorageClient->findProductOfferStorageByReference(
            $productOfferReference,
        );

        if (!$productOfferStorageTransfer) {
            return $itemTransfer;
        }

        return $itemTransfer->setMerchantReference($productOfferStorageTransfer->getMerchantReference());
    }
}
