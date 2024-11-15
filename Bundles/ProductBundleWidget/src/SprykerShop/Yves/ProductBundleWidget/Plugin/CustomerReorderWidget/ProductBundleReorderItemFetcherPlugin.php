<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBundleWidget\Plugin\CustomerReorderWidget;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CustomerReorderWidgetExtension\Dependency\Plugin\ReorderItemFetcherPluginInterface;

/**
 * @deprecated Use {@link \SprykerShop\Yves\ProductBundleWidget\Plugin\CartReorderPage\ProductBundleCartReorderItemCheckboxAttributeExpanderPlugin} and {@link \SprykerShop\Yves\ProductBundleWidget\Plugin\CartReorderPage\ProductBundleCartReorderRequestExpanderPlugin} instead.
 *
 * @method \SprykerShop\Yves\ProductBundleWidget\ProductBundleWidgetFactory getFactory()
 */
class ProductBundleReorderItemFetcherPlugin extends AbstractPlugin implements ReorderItemFetcherPluginInterface
{
    /**
     * {@inheritDoc}
     * - Fetches bundle items according to the data provided by the `bundle-item-identifiers` request parameter key.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ItemTransfer> $itemTransfers
     * @param array<mixed> $requestParams
     *
     * @return array<\Generated\Shared\Transfer\ItemTransfer>
     */
    public function execute(array $itemTransfers, array $requestParams): array
    {
        return $this->getFactory()
            ->createBundleItemFetcher()
            ->fetchSelectedBundleItems($itemTransfers, $requestParams);
    }
}
