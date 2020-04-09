<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductVolumeWidget\PriceProductResolver\PriceProductVolume;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer;
use Generated\Shared\Transfer\PriceProductVolumeTransfer;
use SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Service\PriceProductVolumeWidgetToUtilEncodingServiceInterface;

class PriceProductVolumeResolver implements PriceProductVolumeResolverInterface
{
    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_NET
     */
    protected const PRICE_MODE_NET = 'NET_MODE';

    /**
     * @see \Spryker\Shared\Price\PriceConfig::PRICE_MODE_GROSS
     */
    protected const PRICE_MODE_GROSS = 'GROSS_MODE';

    /**
     * @see \Spryker\Shared\PriceProductVolume\VolumePriceProductConfig::VOLUME_PRICE_TYPE
     */
    protected const VOLUME_PRICE_TYPE = 'volume_prices';

    protected const VOLUME_PRICE_QUANTITY = 'quantity';
    protected const VOLUME_PRICE_MODE_MAPPING = [
        self::PRICE_MODE_NET => 'net_price',
        self::PRICE_MODE_GROSS => 'gross_price',
    ];

    /**
     * @var \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Service\PriceProductVolumeWidgetToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Service\PriceProductVolumeWidgetToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(PriceProductVolumeWidgetToUtilEncodingServiceInterface $utilEncodingService)
    {
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\CurrentProductPriceTransfer $currentProductPriceTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer
     */
    public function resolveVolumeProductPrices(CurrentProductPriceTransfer $currentProductPriceTransfer): PriceProductVolumeCollectionTransfer
    {
        $priceProductVolumeCollectionTransfer = new PriceProductVolumeCollectionTransfer();
        $priceData = $this->utilEncodingService->decodeJson($currentProductPriceTransfer->getPriceData(), true);

        if (isset($priceData[static::VOLUME_PRICE_TYPE])) {
            $priceProductVolumeCollectionTransfer = $this->mapVolumeProductPriceCollection(
                $priceData[static::VOLUME_PRICE_TYPE],
                $priceProductVolumeCollectionTransfer,
                $currentProductPriceTransfer->getPriceMode()
            );
        }

        return $priceProductVolumeCollectionTransfer;
    }

    /**
     * @param array $volumePriceData
     * @param \Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer $priceProductVolumeCollection
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer
     */
    protected function mapVolumeProductPriceCollection(
        array $volumePriceData,
        PriceProductVolumeCollectionTransfer $priceProductVolumeCollection,
        string $priceMode
    ): PriceProductVolumeCollectionTransfer {
        foreach ($volumePriceData as $volumeProductStorageData) {
            if ($this->isVolumePriceDataValid($volumeProductStorageData, $priceMode)) {
                $priceProductVolumeCollection->addVolumePrice(
                    $this->formatPriceProductVolumeTransfer($volumeProductStorageData, $priceMode)
                );
            }
        }

        return $priceProductVolumeCollection;
    }

    /**
     * @param array $priceData
     * @param string $priceMode
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeTransfer
     */
    protected function formatPriceProductVolumeTransfer(array $priceData, string $priceMode): PriceProductVolumeTransfer
    {
        $volumePrice = new PriceProductVolumeTransfer();
        $volumePrice->setQuantity(
            $priceData[static::VOLUME_PRICE_QUANTITY]
        );
        $volumePrice->setPrice(
            $priceData[static::VOLUME_PRICE_MODE_MAPPING[$priceMode]]
        );

        return $volumePrice;
    }

    /**
     * @param array $priceData
     * @param string $priceMode
     *
     * @return bool
     */
    protected function isVolumePriceDataValid(array $priceData, string $priceMode): bool
    {
        if (
            !isset($priceData[static::VOLUME_PRICE_QUANTITY])
            || !is_numeric($priceData[static::VOLUME_PRICE_QUANTITY])
        ) {
            return false;
        }

        if (
            !isset($priceData[static::VOLUME_PRICE_MODE_MAPPING[$priceMode]])
            || !is_numeric($priceData[static::VOLUME_PRICE_MODE_MAPPING[$priceMode]])
        ) {
            return false;
        }

        return true;
    }
}
