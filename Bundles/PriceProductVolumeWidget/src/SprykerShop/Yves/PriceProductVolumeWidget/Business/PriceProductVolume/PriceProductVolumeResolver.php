<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PriceProductVolumeWidget\Business\PriceProductVolume;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\PriceProductVolumeTransfer;
use SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToCurrencyClientInterface;
use SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToPriceClientInterface;
use SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToPriceProductStorageClientInterface;
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
     * @var \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToPriceClientInterface
     */
    protected $priceClient;

    /**
     * @var \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToCurrencyClientInterface
     */
    protected $currencyClient;

    /**
     * @var \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Service\PriceProductVolumeWidgetToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToPriceClientInterface $priceClient
     * @param \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Client\PriceProductVolumeWidgetToCurrencyClientInterface $currencyClient
     * @param \SprykerShop\Yves\PriceProductVolumeWidget\Dependency\Service\PriceProductVolumeWidgetToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        PriceProductVolumeWidgetToPriceProductStorageClientInterface $priceProductStorageClient,
        PriceProductVolumeWidgetToPriceClientInterface $priceClient,
        PriceProductVolumeWidgetToCurrencyClientInterface $currencyClient,
        PriceProductVolumeWidgetToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer
     */
    public function resolveVolumeProductPrices(ProductViewTransfer $productViewTransfer): PriceProductVolumeCollectionTransfer
    {
        $volumeProductPrices = $this->findVolumePriceByIdProductConcrete($productViewTransfer);

        if ($volumeProductPrices != null) {
            return $volumeProductPrices;
        }

        $volumeProductPrices = $this->findVolumePriceByIdProductAbstract($productViewTransfer);

        if ($volumeProductPrices != null) {
            return $volumeProductPrices;
        }

        return new PriceProductVolumeCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer|null
     */
    protected function findVolumePriceByIdProductConcrete(ProductViewTransfer $productViewTransfer): ?PriceProductVolumeCollectionTransfer
    {
        if ($productViewTransfer->getIdProductConcrete() != null) {
            $priceProductTransfers = $this->priceProductStorageClient->getPriceProductConcreteTransfers(
                $productViewTransfer->getIdProductConcrete()
            );

            if (!empty($priceProductTransfers)) {
                return $this->getVolumeProductPricesFromStorageData($priceProductTransfers);
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer|null
     */
    protected function findVolumePriceByIdProductAbstract(ProductViewTransfer $productViewTransfer): ?PriceProductVolumeCollectionTransfer
    {
        if ($productViewTransfer->getIdProductAbstract() != null) {
            $priceProductTransfers = $this->priceProductStorageClient->getPriceProductAbstractTransfers(
                $productViewTransfer->getIdProductAbstract()
            );

            if (!empty($priceProductTransfers)) {
                return $this->getVolumeProductPricesFromStorageData($priceProductTransfers);
            }
        }

        return null;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer
     */
    protected function getVolumeProductPricesFromStorageData(
        array $priceProductTransfers
    ): PriceProductVolumeCollectionTransfer {
        $priceProductVolumeCollection = new PriceProductVolumeCollectionTransfer();

        if (empty($priceProductTransfers)) {
            return $priceProductVolumeCollection;
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (!$this->hasVolumeProductPrice($priceProductTransfer)) {
                continue;
            }

            $this->fillVolumeProductPriceCollectionFromStorageData($priceProductVolumeCollection, $priceProductTransfer);
        }

        return $priceProductVolumeCollection;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function hasVolumeProductPrice(PriceProductTransfer $priceProductTransfer): bool
    {
        $moneyValueTransfer = $priceProductTransfer->getMoneyValue();
        if ($moneyValueTransfer->getCurrency()->getCode() != $this->currencyClient->getCurrent()->getCode()) {
            return false;
        }
        if (!$moneyValueTransfer->getPriceData()) {
            return false;
        }
        $priceData = $this->utilEncodingService->decodeJson($moneyValueTransfer->getPriceData(), true);
        if (!isset($priceData[static::VOLUME_PRICE_TYPE]) || !$priceData[static::VOLUME_PRICE_TYPE]) {
            return false;
        }

        return true;
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductVolumeCollectionTransfer $priceProductVolumeCollection
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function fillVolumeProductPriceCollectionFromStorageData(
        PriceProductVolumeCollectionTransfer $priceProductVolumeCollection,
        PriceProductTransfer $priceProductTransfer
    ): void {
        $volumePriceData = $this->utilEncodingService->decodeJson(
            $priceProductTransfer->getMoneyValue()->getPriceData(),
            true
        )[static::VOLUME_PRICE_TYPE] ?: [];

        foreach ($volumePriceData as $volumeProductStorageData) {
            if ($this->isVolumePriceDataValid($volumeProductStorageData)) {
                $priceProductVolumeCollection->addVolumePrice(
                    $this->formatPriceProductVolumeTransfer($volumeProductStorageData)
                );
            }
        }
    }

    /**
     * @param array $priceData
     *
     * @return \Generated\Shared\Transfer\PriceProductVolumeTransfer
     */
    protected function formatPriceProductVolumeTransfer(array $priceData): PriceProductVolumeTransfer
    {
        $volumePrice = new PriceProductVolumeTransfer();
        $volumePrice->setQuantity(
            $priceData[static::VOLUME_PRICE_QUANTITY]
        );
        $volumePrice->setPrice(
            $priceData[static::VOLUME_PRICE_MODE_MAPPING[$this->priceClient->getCurrentPriceMode()]]
        );

        return $volumePrice;
    }

    /**
     * @param array $priceData
     *
     * @return bool
     */
    protected function isVolumePriceDataValid(array $priceData): bool
    {
        if (!isset($priceData[static::VOLUME_PRICE_QUANTITY])
            || !is_numeric($priceData[static::VOLUME_PRICE_QUANTITY])) {
            return false;
        }

        if (!isset($priceData[static::VOLUME_PRICE_MODE_MAPPING[$this->priceClient->getCurrentPriceMode()]])
            || !is_numeric($priceData[static::VOLUME_PRICE_MODE_MAPPING[$this->priceClient->getCurrentPriceMode()]])) {
            return false;
        }

        return true;
    }
}
