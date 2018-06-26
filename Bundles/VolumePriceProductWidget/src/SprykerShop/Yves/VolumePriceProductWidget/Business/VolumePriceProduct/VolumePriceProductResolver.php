<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\VolumePriceProductWidget\Business\VolumePriceProduct;

use Generated\Shared\Transfer\MoneyValueTransfer;
use Generated\Shared\Transfer\PriceProductStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer;
use Generated\Shared\Transfer\VolumeProductPriceTransfer;
use Spryker\Shared\Price\PriceConfig;
use Spryker\Shared\VolumePriceProduct\VolumePriceProductConfig;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToCurrencyClientInterface;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceClientInterface;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceProductStorageClientInterface;

/**
 * @use Price
 * @use VolumePriceProduct
 */
class VolumePriceProductResolver implements VolumePriceProductResolverInterface
{
    protected const VOLUME_PRICE_TYPE = VolumePriceProductConfig::VOLUME_PRICE_TYPE;
    protected const VOLUME_PRICE_QUANTITY = VolumePriceProductConfig::VOLUME_PRICE_QUANTITY;
    protected const VOLUME_PRICE_MODE_MAPPING = [
        PriceConfig::PRICE_MODE_NET => VolumePriceProductConfig::VOLUME_PRICE_NET,
        PriceConfig::PRICE_MODE_GROSS => VolumePriceProductConfig::VOLUME_PRICE_GROSS,
    ];

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
    public function resolveVolumePriceProduct(ProductViewTransfer $productViewTransfer): VolumeProductPriceCollectionTransfer
    {
        if ($productViewTransfer->getIdProductConcrete() != null) {
            $pricesByProductConcreteId = $this->priceProductStorageClient->findPriceConcreteStorageTransfer(
                $productViewTransfer->getIdProductConcrete()
            );

            if (!empty($pricesByProductConcreteId)) {
                return $this->filterPricesByType($pricesByProductConcreteId);
            }
        }

        if ($productViewTransfer->getIdProductAbstract() != null) {
            $pricesByProductAbstractId = $this->priceProductStorageClient->findPriceAbstractStorageTransfer(
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

        foreach ($priceProductStorageTransfer->getPrices() as $currency => $price) {
            if ($currency != $this->currencyClient->getCurrent()->getCode()) {
                continue;
            }
            if (!$price[MoneyValueTransfer::PRICE_DATA]) {
                continue;
            }
            $priceData = json_decode($price[MoneyValueTransfer::PRICE_DATA], true);
            if (!$priceData[static::VOLUME_PRICE_TYPE]) {
                continue;
            }
            $volumePriceData = $priceData[static::VOLUME_PRICE_TYPE];

            foreach ($volumePriceData as $volumePriceDatum) {
                $result->addVolumePrices(
                    $this->createVolumeProductPriceFromStorageData($volumePriceDatum)
                );
            }
        }

        return $result;
    }

    /**
     * @param array $volumeProductStorageData
     *
     * @return \Generated\Shared\Transfer\VolumeProductPriceTransfer
     */
    protected function createVolumeProductPriceFromStorageData(array $volumeProductStorageData): VolumeProductPriceTransfer
    {
        $volumePrice = new VolumeProductPriceTransfer();
        $volumePrice->setQuantity(
            $volumeProductStorageData[static::VOLUME_PRICE_QUANTITY]
        );
        $volumePrice->setPrice(
            $volumeProductStorageData[static::VOLUME_PRICE_MODE_MAPPING[$this->priceClient->getCurrentPriceMode()]]
        );

        return $volumePrice;
    }

    /**
     * @return \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer
     */
    protected function createVolumeProductPriceCollectionTransfer(): VolumeProductPriceCollectionTransfer
    {
        return new VolumeProductPriceCollectionTransfer();
    }
}
