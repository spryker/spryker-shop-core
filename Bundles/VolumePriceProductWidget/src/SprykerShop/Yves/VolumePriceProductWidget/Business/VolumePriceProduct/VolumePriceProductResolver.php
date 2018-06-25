<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\VolumePriceProductWidget\Business\VolumePriceProduct;

use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToCurrencyClientInterface;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceClientInterface;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceProductStorageClientInterface;

class VolumePriceProductResolver
{
    /**
     * @var \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @param \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceClientInterface $priceClient
     * @param \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToCurrencyClientInterface $currencyClient
     */
    public function __construct(
        VolumePriceProductWidgetToPriceProductStorageClientInterface $priceProductStorageClient,
        VolumePriceProductWidgetToPriceClientInterface $priceClient,
        VolumePriceProductWidgetToCurrencyClientInterface $currencyClient
    ) {
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer
     */
    public function resolveVolumePriceProductForProductConcrete(ProductViewTransfer $productViewTransfer): VolumeProductPriceCollectionTransfer
    {
        if ($productViewTransfer->getIdProductConcrete() !== null) {
            $pricesByProductConcreteId = $this->priceProductStorageClient->findPriceConcreteStorageTransfer(
                $productViewTransfer->getIdProductConcrete()
            );

            if (!empty($pricesByProductConcreteId)) {
                return $this->filterPricesByType($pricesByProductConcreteId);
            }
        }

        if ($productViewTransfer->getIdProductAbstract() !== null) {
            $pricesByProductAbstractId = $this->priceProductStorageClient->findPriceConcreteStorageTransfer(
                $productViewTransfer->getIdProductAbstract()
            );

            if (!empty($pricesByProductAbstractId)) {
                return $this->filterPricesByType($pricesByProductAbstractId);
            }
        }

        return $this->createVolumeProductPriceCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductStorageTransfer $priceProductStorageTransfer
     *
     * @return \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer
     */
    protected function filterPricesByType(PriceProductStorageTransfer $priceProductStorageTransfer): VolumeProductPriceCollectionTransfer
    {
        $result = $this->createVolumeProductPriceCollectionTransfer();
        if (empty($priceProductStorageTransfer->getPrices())) {
            return $result;
        }

//        foreach ($priceProductStorageTransfer->getPrices() as $price) {
            //TODO: fill with volume prices
//        }

        return $result;
    }

    /**
     * @return \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer
     */
    protected function createVolumeProductPriceCollectionTransfer(): VolumeProductPriceCollectionTransfer
    {
        return new VolumeProductPriceCollectionTransfer();
    }
}
