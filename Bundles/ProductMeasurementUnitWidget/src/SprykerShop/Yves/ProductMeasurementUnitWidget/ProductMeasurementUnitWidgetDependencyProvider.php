<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientBridge;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductQuantityStorageClientBridge;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Service\ProductMeasurementUnitWidgetToUtilEncodingServiceBridge;

class ProductMeasurementUnitWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE = 'CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE';
    public const CLIENT_PRODUCT_QUANTITY_STORAGE = 'CLIENT_PRODUCT_QUANTITY_STORAGE';

    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addProductMeasurementUnitStorageClient($container);
        $container = $this->addProductQuantityStorageClient($container);
        $container = $this->addUtilEncodingService($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductMeasurementUnitStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE, function (Container $container) {
            return new ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientBridge(
                $container->getLocator()->productMeasurementUnitStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductQuantityStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_QUANTITY_STORAGE, function (Container $container) {
            return new ProductMeasurementUnitWidgetToProductQuantityStorageClientBridge(
                $container->getLocator()->productQuantityStorage()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addUtilEncodingService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductMeasurementUnitWidgetToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service()
            );
        });

        return $container;
    }
}
