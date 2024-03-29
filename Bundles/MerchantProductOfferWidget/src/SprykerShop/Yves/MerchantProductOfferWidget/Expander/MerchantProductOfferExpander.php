<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface;

class MerchantProductOfferExpander implements MerchantProductOfferExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAM_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface
     */
    protected $merchantProductOfferReader;

    /**
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface $merchantProductOfferReader
     */
    public function __construct(MerchantProductOfferReaderInterface $merchantProductOfferReader)
    {
        $this->merchantProductOfferReader = $merchantProductOfferReader;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array<mixed> $params
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemTransferWithProductOfferReference(
        WishlistItemTransfer $wishlistItemTransfer,
        array $params
    ): WishlistItemTransfer {
        /** @var \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer */
        $wishlistItemTransfer = $this->expandItemWithProductOfferReference($wishlistItemTransfer, $params);

        return $wishlistItemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<mixed> $params
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithProductOfferReference(
        ItemTransfer $itemTransfer,
        array $params
    ): ItemTransfer {
        /** @var \Generated\Shared\Transfer\ItemTransfer $itemTransfer */
        $itemTransfer = $this->expandItemWithProductOfferReference($itemTransfer, $params);

        return $itemTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\WishlistItemTransfer|\Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array<string, mixed> $params
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer|\Generated\Shared\Transfer\ItemTransfer
     */
    protected function expandItemWithProductOfferReference($itemTransfer, array $params)
    {
        if (!isset($params[static::PARAM_PRODUCT_OFFER_REFERENCE])) {
            return $itemTransfer;
        }

        $productOfferReference = $params[static::PARAM_PRODUCT_OFFER_REFERENCE] ?: null;

        if (!$productOfferReference) {
            return $itemTransfer;
        }

        $merchantReference = $this->merchantProductOfferReader
            ->findMerchantReferenceByProductOfferReference($productOfferReference);

        return $itemTransfer->setProductOfferReference($productOfferReference)
            ->setMerchantReference($merchantReference);
    }
}
