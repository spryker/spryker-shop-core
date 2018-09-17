<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBarcodeWidget\Widget;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductBarcodeWidget\ProductBarcodeWidgetFactory getFactory()
 */
class ProductBarcodeWidget extends AbstractWidget
{
     /**
      * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
      * @param string|null $barcodeGeneratorPlugin
      */
    public function __construct(ProductViewTransfer $productViewTransfer, ?string $barcodeGeneratorPlugin = null)
    {
        $this->addParameter('barcodeResponseTransfer', $this->getBarcodeResponseTransfer($productViewTransfer, $barcodeGeneratorPlugin));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ProductBarcodeWidget';
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
     * @param string|null $barcodeGeneratorPlugin
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
