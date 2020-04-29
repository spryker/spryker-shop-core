<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ReturnBarcodeWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ProductBarcodeWidget\ProductBarcodeWidgetFactory getFactory()
 */
class ReturnBarcodeWidget extends AbstractWidget
{
    protected const PARAMETER_RETURN_REFERENCE = 'returnReference';
    protected const PARAMETER_SKU = 'sku';

     /**
      * @param \Generated\Shared\Transfer\ProductViewTransfer|string $productViewTransfer
      * @param string $barcodeGeneratorPlugin
      */
    public function __construct(string $returnReference, string $sku)
    {
//        $this->addParameter(static::PARAMETER_RETURN_REFERENCE, $returnReference);
//        $this->addParameter(static::PARAMETER_SKU, $sku);
//        $this->addParameter('barcodeResponseTransfer', $this->getBarcodeResponseTransfer($productViewTransfer, $barcodeGeneratorPlugin));
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'ReturnBarcodeWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@ReturnBarcodeWidget/views/barcode/barcode.twig';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string|null $barcodeGeneratorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
//    protected function getBarcodeResponseTransfer(ProductViewTransfer $productViewTransfer, ?string $barcodeGeneratorPlugin): BarcodeResponseTransfer
//    {
//        $sku = $productViewTransfer->requireSku()->getSku();
//
//        return $this->getFactory()
//            ->getProductBarcodeClient()
//            ->generateBarcodeBySku($sku, $barcodeGeneratorPlugin);
//    }
}
