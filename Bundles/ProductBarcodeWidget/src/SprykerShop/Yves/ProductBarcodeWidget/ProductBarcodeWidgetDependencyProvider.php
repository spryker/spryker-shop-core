<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductBarcodeWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductBarcodeWidget\Dependency\Client\ProductBarcodeWidgetToProductBarcodeClientBridge;

class ProductBarcodeWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_BARCODE = 'CLIENT_PRODUCT_BARCODE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addProductBarcodeClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductBarcodeClient(Container $container): Container
    {
        $container[static::CLIENT_PRODUCT_BARCODE] = function (Container $container) {
            return new ProductBarcodeWidgetToProductBarcodeClientBridge($container->getLocator()->productBarcode()->client());
        };

        return $container;
    }
}
