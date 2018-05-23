<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BarcodeWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\BarcodeWidget\Dependency\Facade\BarcodeWidgetToProductBarcodeClientBridge;

class BarcodeWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_PRODUCT_BARCODE = 'CLIENT_PRODUCT_BARCODE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addProductBarcodeClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductBarcodeClient(Container $container)
    {
        $container[self::CLIENT_PRODUCT_BARCODE] = function (Container $container) {
            return new BarcodeWidgetToProductBarcodeClientBridge($container->getLocator()->productBarcode()->client());
        };

        return $container;
    }
}
