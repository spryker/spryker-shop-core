<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToLocaleClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductQuantityStorageClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Service\ProductMeasurementUnitWidgetToUtilEncodingServiceInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Service\ProductMeasurementUnitWidgetToUtilNumberServiceInterface;

class ProductMeasurementUnitWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface
     */
    public function getProductMeasurementUnitStorageClient(): ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitWidgetDependencyProvider::CLIENT_PRODUCT_MEASUREMENT_UNIT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductQuantityStorageClientInterface
     */
    public function getProductQuantityStorageClient(): ProductMeasurementUnitWidgetToProductQuantityStorageClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitWidgetDependencyProvider::CLIENT_PRODUCT_QUANTITY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Service\ProductMeasurementUnitWidgetToUtilEncodingServiceInterface
     */
    public function getUtilEncodingService(): ProductMeasurementUnitWidgetToUtilEncodingServiceInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitWidgetDependencyProvider::SERVICE_UTIL_ENCODING);
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToLocaleClientInterface
     */
    public function getLocaleClient(): ProductMeasurementUnitWidgetToLocaleClientInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitWidgetDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Service\ProductMeasurementUnitWidgetToUtilNumberServiceInterface
     */
    public function getUtilNumberService(): ProductMeasurementUnitWidgetToUtilNumberServiceInterface
    {
        return $this->getProvidedDependency(ProductMeasurementUnitWidgetDependencyProvider::SERVICE_UTIL_NUMBER);
    }
}
