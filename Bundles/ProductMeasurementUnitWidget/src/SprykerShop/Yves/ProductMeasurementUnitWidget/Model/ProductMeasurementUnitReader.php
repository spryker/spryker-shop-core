<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Model;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementUnitMapperInterface;

/**
 * @deprecated Use ProductMeasurementUnitStorageClientInterface instead.
 */
class ProductMeasurementUnitReader implements ProductMeasurementUnitReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface
     */
    protected $productMeasurementUnitStorageClient;

    /**
     * @var \SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementUnitMapperInterface
     */
    protected $productMeasurementUnitMapper;

    /**
     * @param \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     * @param \SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementUnitMapperInterface $productMeasurementUnitMapper
     */
    public function __construct(
        ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient,
        ProductMeasurementUnitMapperInterface $productMeasurementUnitMapper
    ) {
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
        $this->productMeasurementUnitMapper = $productMeasurementUnitMapper;
    }

    /**
     * @deprecated Use ProductMeasurementUnitStorageClientInterface::findProductMeasurementUnit() instead.
     *
     * @param int $idProductMeasurementUnit
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null
     */
    public function findProductMeasurementUnit(int $idProductMeasurementUnit): ?ProductMeasurementUnitTransfer
    {
        $productMeasurementUnitStorageTransfer = $this->productMeasurementUnitStorageClient->findProductMeasurementUnitStorage($idProductMeasurementUnit);

        if ($productMeasurementUnitStorageTransfer !== null) {
            return $this->productMeasurementUnitMapper->mapProductMeasurementUnit(
                $productMeasurementUnitStorageTransfer,
                new ProductMeasurementUnitTransfer()
            );
        }

        return null;
    }
}
