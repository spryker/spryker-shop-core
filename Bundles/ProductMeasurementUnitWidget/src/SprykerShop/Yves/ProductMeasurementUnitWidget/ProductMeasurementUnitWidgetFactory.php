<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductQuantityStorageClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementSalesUnitMapper;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementSalesUnitMapperInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementUnitMapper;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementUnitMapperInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementBaseUnitReader;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementBaseUnitReaderInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementSalesUnitReader;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementSalesUnitReaderInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementUnitReader;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementUnitReaderInterface;

class ProductMeasurementUnitWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementBaseUnitReaderInterface
     */
    public function createProductMeasurementBaseUnitReader(): ProductMeasurementBaseUnitReaderInterface
    {
        return new ProductMeasurementBaseUnitReader(
            $this->getProductMeasurementUnitStorageClient(),
            $this->createProductMeasurementUnitReader()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementUnitReaderInterface
     */
    public function createProductMeasurementUnitReader(): ProductMeasurementUnitReaderInterface
    {
        return new ProductMeasurementUnitReader(
            $this->getProductMeasurementUnitStorageClient(),
            $this->createProductMeasurementUnitMapper()
        );
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementSalesUnitReaderInterface
     */
    public function createProductMeasurementSalesUnitReader(): ProductMeasurementSalesUnitReaderInterface
    {
        return new ProductMeasurementSalesUnitReader(
            $this->getProductMeasurementUnitStorageClient(),
            $this->createProductMeasurementUnitReader(),
            $this->createProductMeasurementSalesUnitMapper()
        );
    }

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
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementUnitMapperInterface
     */
    public function createProductMeasurementUnitMapper(): ProductMeasurementUnitMapperInterface
    {
        return new ProductMeasurementUnitMapper();
    }

    /**
     * @return \SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementSalesUnitMapperInterface
     */
    public function createProductMeasurementSalesUnitMapper(): ProductMeasurementSalesUnitMapperInterface
    {
        return new ProductMeasurementSalesUnitMapper();
    }
}
