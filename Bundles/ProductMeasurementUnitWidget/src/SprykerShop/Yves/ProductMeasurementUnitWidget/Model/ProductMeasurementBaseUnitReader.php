<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Model;

use Generated\Shared\Transfer\ProductMeasurementUnitTransfer;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface;

/**
 * @deprecated ProductMeasurementUnitStorageModule
 */
class ProductMeasurementBaseUnitReader implements ProductMeasurementBaseUnitReaderInterface
{
    /**
     * @var \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface
     */
    protected $productMeasurementUnitStorageClient;

    /**
     * @var \SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementSalesUnitMapperInterface
     */
    protected $productMeasurementSalesUnitMapper;

    /**
     * @var \SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementUnitReaderInterface
     */
    protected $productMeasurementUnitReader;

    /**
     * @param \SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient
     * @param \SprykerShop\Yves\ProductMeasurementUnitWidget\Model\ProductMeasurementUnitReaderInterface $productMeasurementUnitReader
     */
    public function __construct(
        ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient,
        ProductMeasurementUnitReaderInterface $productMeasurementUnitReader
    ) {
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
        $this->productMeasurementUnitReader = $productMeasurementUnitReader;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementUnitTransfer|null
     */
    public function findProductMeasurementBaseUnitByIdProductConcrete(int $idProductConcrete): ?ProductMeasurementUnitTransfer
    {
        $productConcreteMeasurementUnitStorageTransfer = $this->productMeasurementUnitStorageClient
            ->findProductConcreteMeasurementUnitStorage($idProductConcrete);

        if ($productConcreteMeasurementUnitStorageTransfer !== null) {
            return $this->productMeasurementUnitReader->findProductMeasurementUnit(
                $productConcreteMeasurementUnitStorageTransfer->getBaseUnit()->getIdProductMeasurementUnit()
            );
        }

        return null;
    }
}
