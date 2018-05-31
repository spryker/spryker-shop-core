<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBarcodeWidget\Dependency\Client;

use Generated\Shared\Transfer\BarcodeResponseTransfer;

class ProductBarcodeWidgetToProductBarcodeClientBridge implements ProductBarcodeWidgetToProductBarcodeClientInterface
{
    /**
     * @var \Spryker\Client\ProductBarcode\ProductBarcodeClientInterface
     */
    protected $productBarcodeClient;

    /**
     * @param \Spryker\Client\ProductBarcode\ProductBarcodeClientInterface $productBarcodeClient
     */
    public function __construct($productBarcodeClient)
    {
        $this->productBarcodeClient = $productBarcodeClient;
    }

    /**
     * @param string $sku
     * @param string|null $barcodeGeneratorPlugin
     *
     * @return \Generated\Shared\Transfer\BarcodeResponseTransfer
     */
    public function generateBarcodeBySku(
        string $sku,
        ?string $barcodeGeneratorPlugin = null
    ): BarcodeResponseTransfer {
        return $this->productBarcodeClient
            ->generateBarcodeBySku($sku, $barcodeGeneratorPlugin);
    }
}
