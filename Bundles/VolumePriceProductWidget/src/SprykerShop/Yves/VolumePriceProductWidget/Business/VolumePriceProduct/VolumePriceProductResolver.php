<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\VolumePriceProductWidget\Business\VolumePriceProduct;

use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer;
use Generated\Shared\Transfer\VolumeProductPriceTransfer;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToCurrencyClientInterface;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceClientInterface;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceProductStorageClientInterface;
use SprykerShop\Yves\VolumePriceProductWidget\Dependency\Service\VolumePriceProductWidgetToUtilEncodingServiceInterface;

class VolumePriceProductResolver implements VolumePriceProductResolverInterface
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
     * @var \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Service\VolumePriceProductWidgetToUtilEncodingServiceInterface
     */
    protected $utilEncodingService;

    /**
     * @param \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToPriceClientInterface $priceClient
     * @param \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Client\VolumePriceProductWidgetToCurrencyClientInterface $currencyClient
     * @param \SprykerShop\Yves\VolumePriceProductWidget\Dependency\Service\VolumePriceProductWidgetToUtilEncodingServiceInterface $utilEncodingService
     */
    public function __construct(
        VolumePriceProductWidgetToPriceProductStorageClientInterface $priceProductStorageClient,
        VolumePriceProductWidgetToPriceClientInterface $priceClient,
        VolumePriceProductWidgetToCurrencyClientInterface $currencyClient,
        VolumePriceProductWidgetToUtilEncodingServiceInterface $utilEncodingService
    ) {
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->priceClient = $priceClient;
        $this->currencyClient = $currencyClient;
        $this->utilEncodingService = $utilEncodingService;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer
     */
    public function resolveVolumeProductPrices(ProductViewTransfer $productViewTransfer): VolumeProductPriceCollectionTransfer
    {
        $volumeProductPrices = $this->findVolumePriceByIdProductConcrete($productViewTransfer);

        if ($volumeProductPrices != null) {
            return $volumeProductPrices;
        }

        $volumeProductPrices = $this->findVolumePriceByIdProductAbstract($productViewTransfer);

        if ($volumeProductPrices != null) {
            return $volumeProductPrices;
        }

        return new VolumeProductPriceCollectionTransfer();
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer|null
     */
    protected function findVolumePriceByIdProductConcrete(ProductViewTransfer $productViewTransfer): ?VolumeProductPriceCollectionTransfer
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
     * @return \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer|null
     */
    protected function findVolumePriceByIdProductAbstract(ProductViewTransfer $productViewTransfer): ?VolumeProductPriceCollectionTransfer
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
     * @return \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer
     */
    protected function getVolumeProductPricesFromStorageData(
        array $priceProductTransfers
    ): VolumeProductPriceCollectionTransfer {
        $volumeProductPriceCollectionTransfer = new VolumeProductPriceCollectionTransfer();

        if (empty($priceProductTransfers)) {
            return $volumeProductPriceCollectionTransfer;
        }

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (!$this->hasVolumeProductPrice($priceProductTransfer)) {
                continue;
            }

            $this->fillVolumeProductPriceCollectionFromStorageData($volumeProductPriceCollectionTransfer, $priceProductTransfer);
        }

        return $volumeProductPriceCollectionTransfer;
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
     * @param \Generated\Shared\Transfer\VolumeProductPriceCollectionTransfer $volumeProductPriceCollectionTransfer
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return void
     */
    protected function fillVolumeProductPriceCollectionFromStorageData(
        VolumeProductPriceCollectionTransfer $volumeProductPriceCollectionTransfer,
        PriceProductTransfer $priceProductTransfer
    ): void {
        $volumePriceData = $this->utilEncodingService->decodeJson(
            $priceProductTransfer->getMoneyValue()->getPriceData(),
            true
        )[static::VOLUME_PRICE_TYPE] ?: [];

        foreach ($volumePriceData as $volumeProductStorageData) {
            if ($this->isVolumePriceDataValid($volumeProductStorageData)) {
                $volumeProductPriceCollectionTransfer->addVolumePrice(
                    $this->formatVolumeProductPriceTransfer($volumeProductStorageData)
                );
            }
        }
    }

    /**
     * @param array $priceData
     *
     * @return \Generated\Shared\Transfer\VolumeProductPriceTransfer
     */
    protected function formatVolumeProductPriceTransfer(array $priceData): VolumeProductPriceTransfer
    {
        $volumePrice = new VolumeProductPriceTransfer();
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
