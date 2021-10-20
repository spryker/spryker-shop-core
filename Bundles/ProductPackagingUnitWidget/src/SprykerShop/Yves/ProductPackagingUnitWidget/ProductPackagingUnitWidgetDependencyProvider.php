<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductPackagingUnitWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client\ProductPackagingUnitWidgetToProductMeasurementUnitStorageClientBridge;
use SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client\ProductPackagingUnitWidgetToProductPackagingUnitStorageClientBridge;
use SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Client\ProductPackagingUnitWidgetToProductQuantityStorageClientBridge;
use SprykerShop\Yves\ProductPackagingUnitWidget\Dependency\Service\ProductPackagingUnitWidgetToUtilEncodingServiceBridge;

class ProductPackagingUnitWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_PACKAGING_UNIT_STORAGE = 'CLIENT_PRODUCT_PACKAGING_UNIT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE = 'CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE';

    /**
     * @var string
     */
    public const CLIENT_PRODUCT_QUANTITY_STORAGE = 'CLIENT_PRODUCT_QUANTITY_STORAGE';

    /**
     * @var string
     */
    public const SERVICE_UTIL_ENCODING = 'SERVICE_UTIL_ENCODING';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addProductPackagingUnitStorageClient($container);
        $container = $this->addProductMeasurementUnitStorageClient($container);
        $container = $this->addProductQuantityStorageClient($container);
        $container = $this->addEncodeService($container);

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
            return new ProductPackagingUnitWidgetToProductMeasurementUnitStorageClientBridge(
                $container->getLocator()->productMeasurementUnitStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductPackagingUnitStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_PACKAGING_UNIT_STORAGE, function (Container $container) {
            return new ProductPackagingUnitWidgetToProductPackagingUnitStorageClientBridge(
                $container->getLocator()->productPackagingUnitStorage()->client(),
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
            return new ProductPackagingUnitWidgetToProductQuantityStorageClientBridge(
                $container->getLocator()->productQuantityStorage()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addEncodeService(Container $container): Container
    {
        $container->set(static::SERVICE_UTIL_ENCODING, function (Container $container) {
            return new ProductPackagingUnitWidgetToUtilEncodingServiceBridge(
                $container->getLocator()->utilEncoding()->service(),
            );
        });

        return $container;
    }
}
