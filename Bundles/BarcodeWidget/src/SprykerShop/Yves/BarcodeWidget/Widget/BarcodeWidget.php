<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BarcodeWidget\Widget;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\BarcodeWidget\BarcodeWidgetFactory getFactory()
 */
class BarcodeWidget extends AbstractWidget
{
    protected const PARAMETER_BARCODE_RESPONSE = 'barcodeResponse';

     /**
      * @param string $generationText
      * @param string|null $barcodeGeneratorPlugin
      */
    public function __construct(string $generationText, ?string $barcodeGeneratorPlugin = null)
    {
        $this->addBarcodeResponseParameter($generationText, $barcodeGeneratorPlugin);
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'BarcodeWidget';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@BarcodeWidget/views/barcode/barcode.twig';
    }

    /**
     * @param string $generationText
     * @param string|null $barcodeGeneratorPlugin
     *
     * @return void
     */
    protected function addBarcodeResponseParameter(string $generationText, ?string $barcodeGeneratorPlugin): void
    {
        $this->addParameter(
            static::PARAMETER_BARCODE_RESPONSE,
            $this->getFactory()
                ->getBarcodeService()
                ->generateBarcode($generationText, $barcodeGeneratorPlugin)
        );
    }
}
