<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Expander;

use Generated\Shared\Transfer\ItemTransfer;
use Generated\Shared\Transfer\WishlistItemTransfer;
use SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReaderInterface;

class MerchantProductExpander implements MerchantProductExpanderInterface
{
    /**
     * @var string
     */
    protected const PARAM_MERCHANT_REFERENCE = 'merchant_reference';

    /**
     * @var \SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReaderInterface
     */
    protected $merchantProductReader;

    /**
     * @param \SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReaderInterface $merchantProductReader
     */
    public function __construct(MerchantProductReaderInterface $merchantProductReader)
    {
        $this->merchantProductReader = $merchantProductReader;
    }

    /**
     * @phpstan-param array<mixed> $params
     *
     * @param \Generated\Shared\Transfer\WishlistItemTransfer $wishlistItemTransfer
     * @param array $params
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\WishlistItemTransfer
     */
    public function expandWishlistItemTransferWithMerchantReference(
        WishlistItemTransfer $wishlistItemTransfer,
        array $params,
        string $locale
    ): WishlistItemTransfer {
        if (empty($params[static::PARAM_MERCHANT_REFERENCE])) {
            return $wishlistItemTransfer;
        }

        /** @var string $concreteSku */
        $concreteSku = $wishlistItemTransfer->getSku();

        $merchantReference = $this->merchantProductReader
            ->findMerchantReferenceByConcreteSku($concreteSku, $locale);

        if ($params[static::PARAM_MERCHANT_REFERENCE] !== $merchantReference) {
            return $wishlistItemTransfer;
        }

        return $wishlistItemTransfer->setMerchantReference($merchantReference);
    }

    /**
     * @phpstan-param array<mixed> $params
     *
     * @param \Generated\Shared\Transfer\ItemTransfer $itemTransfer
     * @param array $params
     * @param string $locale
     *
     * @return \Generated\Shared\Transfer\ItemTransfer
     */
    public function expandItemTransferWithMerchantReference(
        ItemTransfer $itemTransfer,
        array $params,
        string $locale
    ): ItemTransfer {
        if (empty($params[static::PARAM_MERCHANT_REFERENCE])) {
            return $itemTransfer;
        }

        /** @var string $concreteSku */
        $concreteSku = $itemTransfer->getSku();

        $merchantReference = $this->merchantProductReader
            ->findMerchantReferenceByConcreteSku($concreteSku, $locale);

        if ($params[static::PARAM_MERCHANT_REFERENCE] !== $merchantReference) {
            return $itemTransfer;
        }

        return $itemTransfer->setMerchantReference($merchantReference);
    }
}
