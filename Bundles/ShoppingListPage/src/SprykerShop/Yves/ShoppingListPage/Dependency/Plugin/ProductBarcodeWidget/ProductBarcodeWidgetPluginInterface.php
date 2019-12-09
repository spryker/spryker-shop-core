<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductBarcodeWidget;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductBarcodeWidget\Widget\ProductBarcodeWidget instead.
 */
interface ProductBarcodeWidgetPluginInterface extends WidgetPluginInterface
{
    public const NAME = 'ProductBarcodeWidgetPlugin';

    /**
     * @api
     *
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string|null $barcodeGeneratorPlugin
     *
     * @return void
     */
    public function initialize(ProductViewTransfer $productViewTransfer, ?string $barcodeGeneratorPlugin = null): void;
}
