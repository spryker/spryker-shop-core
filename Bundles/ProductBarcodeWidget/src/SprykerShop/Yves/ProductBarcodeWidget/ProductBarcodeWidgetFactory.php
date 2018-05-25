<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBarcodeWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductBarcodeWidget\Dependency\Facade\ProductBarcodeWidgetToProductBarcodeClientInterface;

class ProductBarcodeWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductBarcodeWidget\Dependency\Facade\ProductBarcodeWidgetToProductBarcodeClientInterface
     */
    public function getProductBarcodeClient(): ProductBarcodeWidgetToProductBarcodeClientInterface
    {
        return $this->getProvidedDependency(ProductBarcodeWidgetDependencyProvider::CLIENT_PRODUCT_BARCODE);
    }
}
