<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductGroupWidgetExtension\Dependency\Plugin;

/**
 * Use this plugin to expand `ProductViewTransfer` search results with additional data in bulk.
 */
interface ProductViewBulkExpanderPluginInterface
{
    /**
     * Specification:
     *  - Expands product view data transfer objects with additional data.
     *
     * @api
     *
     * @param array<\Generated\Shared\Transfer\ProductViewTransfer> $productViewTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductViewTransfer>
     */
    public function execute(array $productViewTransfers): array;
}
