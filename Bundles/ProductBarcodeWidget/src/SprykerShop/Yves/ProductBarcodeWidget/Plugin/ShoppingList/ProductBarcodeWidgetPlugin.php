<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBarcodeWidget\Plugin\ShoppingList;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductBarcodeWidget\ProductBarcodeWidgetPluginInterface;

/**
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
        $this->addParameter('barcodeResponseTransfer', $this->getBarcodeResponseTransfer($productViewTransfer, $barcodeGeneratorPlugin));
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
        return '@ProductBarcodeWidget/views/barcode/barcode.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param null|string $barcodeGeneratorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    protected function getBarcodeResponseTransfer(ProductViewTransfer $productViewTransfer, ?string $barcodeGeneratorPlugin): BarcodeResponseTransfer
    {
        $sku = $productViewTransfer->requireSku()->getSku();

        return $this->getFactory()
            ->getProductBarcodeClient()
            ->generateBarcodeBySku($sku, $barcodeGeneratorPlugin);
    }
}
