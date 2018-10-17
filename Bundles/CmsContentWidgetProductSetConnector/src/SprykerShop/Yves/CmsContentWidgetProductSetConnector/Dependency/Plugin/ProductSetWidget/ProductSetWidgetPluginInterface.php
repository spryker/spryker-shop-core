<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsContentWidgetProductSetConnector\Dependency\Plugin\ProductSetWidget;

use Generated\Shared\Transfer\ProductSetDataStorageTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use molecule('product-set', 'ProductSetWidget') instead.
 */
interface ProductSetWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductSetWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductSetDataStorageTransfer $productSetDataStorageTransfer
     * @param \Generated\Shared\Transfer\ProductViewTransfer[] $productViewTransfers
     *
     * @return void
     */
    public function initialize(ProductSetDataStorageTransfer $productSetDataStorageTransfer, array $productViewTransfers): void;
}
