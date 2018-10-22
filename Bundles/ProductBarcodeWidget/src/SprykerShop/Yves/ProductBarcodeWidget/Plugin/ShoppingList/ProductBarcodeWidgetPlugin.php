<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBarcodeWidget\Plugin\ShoppingList;

use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ProductBarcodeWidget\Widget\ProductBarcodeWidget;
use SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductBarcodeWidget\ProductBarcodeWidgetPluginInterface;

/**
 * @deprecated Use \SprykerShop\Yves\ProductBarcodeWidget\Widget\ProductBarcodeWidget instead.
 *
 * @method \SprykerShop\Yves\ProductBarcodeWidget\ProductBarcodeWidgetFactory getFactory()
 */
class ProductBarcodeWidgetPlugin extends AbstractWidgetPlugin implements ProductBarcodeWidgetPluginInterface
{
     /**
      * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
      * @param string|null $barcodeGeneratorPlugin
      *
      * @return void
      */
    public function initialize(ProductViewTransfer $productViewTransfer, ?string $barcodeGeneratorPlugin = null): void
    {
        $widget = new ProductBarcodeWidget($productViewTransfer, $barcodeGeneratorPlugin);

        $this->parameters = $widget->getParameters();
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return ProductBarcodeWidget::getTemplate();
    }
}
