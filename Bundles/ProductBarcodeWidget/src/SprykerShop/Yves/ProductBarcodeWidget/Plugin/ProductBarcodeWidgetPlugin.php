<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBarcodeWidget\Plugin;

use Generated\Shared\Transfer\ProductConcreteTransfer;
use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\ShoppingListPage\Dependency\Plugin\ProductBarcodeWidget\ProductBarcodeWidgetPluginInterface;

/**
 * @method \SprykerShop\Yves\ProductBarcodeWidget\ProductBarcodeWidgetFactory getFactory()
 */
class ProductBarcodeWidgetPlugin extends AbstractWidgetPlugin implements ProductBarcodeWidgetPluginInterface
{
     /**
     * @param string $productSku
     *
     * @return void
     */
    public function initialize(string $productSku): void
    {
        $productConcreteTransfer = (new ProductConcreteTransfer())->setSku($productSku);

        $barcodeResponseTransfer = $this->getFactory()
            ->getProductBarcodeClient()
            ->generateBarcode($productConcreteTransfer);

        $this->addParameter('productSku', $productSku);
        $this->addParameter('barcodeResponseTransfer', $barcodeResponseTransfer);
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
        return '@ProductBarcodeWidget/views/barcode.twig';
    }
}
