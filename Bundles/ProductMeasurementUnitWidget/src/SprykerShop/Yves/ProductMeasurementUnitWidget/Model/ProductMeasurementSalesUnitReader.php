<?php

namespace SprykerShop\Yves\ProductMeasurementUnitWidget\Model;

use Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Dependency\Client\ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface;
use SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementSalesUnitMapperInterface;

class ProductMeasurementSalesUnitReader implements ProductMeasurementSalesUnitReaderInterface
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
     * @param \SprykerShop\Yves\ProductMeasurementUnitWidget\Mapper\ProductMeasurementSalesUnitMapperInterface $productMeasurementSalesUnitMapper
     */
    public function __construct(
        ProductMeasurementUnitWidgetToProductMeasurementUnitStorageClientInterface $productMeasurementUnitStorageClient,
        ProductMeasurementUnitReaderInterface $productMeasurementUnitReader,
        ProductMeasurementSalesUnitMapperInterface $productMeasurementSalesUnitMapper
    ) {
        $this->productMeasurementUnitStorageClient = $productMeasurementUnitStorageClient;
        $this->productMeasurementUnitReader = $productMeasurementUnitReader;
        $this->productMeasurementSalesUnitMapper = $productMeasurementSalesUnitMapper;
    }

    /**
     * @param int $idProductConcrete
     *
     * @return \Generated\Shared\Transfer\ProductMeasurementSalesUnitTransfer[]|null
     */
    public function findProductMeasurementSalesUnitByIdProductConcrete(int $idProductConcrete): ?array
    {
        $productConcreteMeasurementUnitStorageTransfer = $this->productMeasurementUnitStorageClient
            ->findProductConcreteMeasurementUnitStorage($idProductConcrete);

        if ($productConcreteMeasurementUnitStorageTransfer !== null) {
            $productMeasurementSalesUnits = [];
            $defaultFound = false;
            foreach ($productConcreteMeasurementUnitStorageTransfer->getSalesUnits() as $productConcreteMeasurementSalesUnitTransfer) {
                if ($productConcreteMeasurementSalesUnitTransfer->getIsDisplayed() !== true) {
                    continue;
                }

                if ($productConcreteMeasurementSalesUnitTransfer->getIsDefault()) {
                    $defaultFound = true;
                }

                $productMeasurementUnitTransfer = $this->productMeasurementUnitReader->findProductMeasurementUnit(
                    $productConcreteMeasurementSalesUnitTransfer->getIdProductMeasurementUnit()
                );

                $productMeasurementSalesUnit = $this->productMeasurementSalesUnitMapper->mapProductMeasurementSalesUnitTransfer(
                    $productConcreteMeasurementSalesUnitTransfer,
                    new ProductMeasurementSalesUnitTransfer()
                );

                $productMeasurementSalesUnit->setProductMeasurementUnit($productMeasurementUnitTransfer);
                $productMeasurementSalesUnits[] = $productMeasurementSalesUnit;
            }

            if ($defaultFound !== true && count($productMeasurementSalesUnits)) {
                $productMeasurementSalesUnits[0]->setIsDefault(true);
            }

            return $productMeasurementSalesUnits;
        }

        return null;
    }
}