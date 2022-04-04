<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin;

/**
 * Provides capabilities to expand product offer transfers collection.
 */
interface MerchantProductOfferCollectionExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands array of product offer transfers used as form choices.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    public function expand(array $productOfferTransfers): array;
}
