<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ReturnBarcodeWidget\Widget;

use Generated\Shared\Transfer\BarcodeResponseTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\ReturnBarcodeWidget\ReturnBarcodeWidgetFactory getFactory()
 */
class ReturnBarcodeWidget extends AbstractWidget
{
     /**
      * @param string $generationText
      * @param string|null $barcodeGeneratorPlugin
      */
    public function __construct(string $generationText, ?string $barcodeGeneratorPlugin = null)
    {
        $this->addParameter('barcodeResponseTransfer', $this->getBarcodeResponseTransfer($generationText, $barcodeGeneratorPlugin));
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
     * @param string $generationText
     * @param string|null $barcodeGeneratorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    protected function getBarcodeResponseTransfer(string $generationText, ?string $barcodeGeneratorPlugin): BarcodeResponseTransfer
    {
        return $this->getFactory()
            ->getBarcodeService()
            ->generateBarcode($generationText, $barcodeGeneratorPlugin);
    }
}
