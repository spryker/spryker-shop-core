<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidgetExtension\Dependency\Plugin;

/**
 * Use this plugin for expand batch `ProductViewTransfer` by data (review rating) using search results for each products.
 */
interface ProductViewBulkExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands product view data transfer objects with additional data.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return \Generated\Shared\Transfer\ProductViewTransfer[]
     */
    public function execute(array $productViewTransfers): array;
}
