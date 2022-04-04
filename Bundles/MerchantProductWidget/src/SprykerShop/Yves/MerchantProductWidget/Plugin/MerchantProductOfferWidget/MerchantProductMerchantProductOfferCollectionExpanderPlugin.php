<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Plugin\MerchantProductOfferWidget;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin\MerchantProductOfferCollectionExpanderPluginInterface;

/**
 * @method \SprykerShop\Yves\MerchantProductWidget\MerchantProductWidgetFactory getFactory()
 */
class MerchantProductMerchantProductOfferCollectionExpanderPlugin extends AbstractPlugin implements MerchantProductOfferCollectionExpanderPluginInterface
{
    /**
     * {@inheritDoc}
     * - Finds merchant product by sku and expands form choices with a merchant product's value.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    public function expand(array $productOfferTransfers): array
    {
        return $this->getFactory()
            ->createMerchantProductOfferCollectionExpander()
            ->expand(
                $productOfferTransfers,
                $this->getLocale(),
            );
    }
}
