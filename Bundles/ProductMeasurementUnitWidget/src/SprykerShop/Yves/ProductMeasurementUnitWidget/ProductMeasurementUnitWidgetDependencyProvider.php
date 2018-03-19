<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientBridge;

class ProductMeasurementUnitWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE = 'CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addProductMeasurementUnitStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductMeasurementUnitStorageClient(Container $container): Container
    {
        $container[self::CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE] = function (Container $container) {
            return new ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientBridge($container->getLocator()->productMeasurementUnitStorage()->client());
        };

        return $container;
    }
}
