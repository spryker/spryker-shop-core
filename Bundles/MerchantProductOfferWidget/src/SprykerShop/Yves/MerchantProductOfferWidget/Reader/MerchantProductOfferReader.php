<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Reader;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Shared\MerchantProductOfferWidget\MerchantProductOfferWidgetConfig;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToPriceProductStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Service\MerchantProductOfferWidgetToPriceProductClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface;

class MerchantProductOfferReader implements MerchantProductOfferReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Service\MerchantProductOfferWidgetToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface
     */
    protected $shopContextResolver;

    /**
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface $merchantStorageClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Service\MerchantProductOfferWidgetToPriceProductClientInterface $priceProductClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface $shopContextResolver
     */
    public function __construct(
        MerchantProductOfferWidgetToMerchantStorageClientInterface $merchantStorageClient,
        MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient,
        MerchantProductOfferWidgetToPriceProductClientInterface $priceProductClient,
        MerchantProductOfferWidgetToPriceProductStorageClientInterface $priceProductStorageClient,
        ShopContextResolverInterface $shopContextResolver
    ) {
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
        $this->priceProductClient = $priceProductClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->shopContextResolver = $shopContextResolver;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageTransfer[]
     */
    public function getProductOfferCollection(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return [];
        }
        $productOfferStorageList = [];

        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->fromArray($this->shopContextResolver->resolve()->modifiedToArray(), true);
        $productOfferStorageCriteriaTransfer->setSku($productViewTransfer->getSku());

        $productOfferStorageCollection = $this->merchantProductOfferStorageClient->getProductOfferStorageCollection($productOfferStorageCriteriaTransfer);
        $productOffersStorageTransfers = $productOfferStorageCollection->getProductOffersStorage();
        $merchantIds = array_map(function (ProductOfferStorageTransfer $productOffersStorageTransfer) {
            return $productOffersStorageTransfer->getIdMerchant();
        }, $productOffersStorageTransfers->getArrayCopy());

        $priceProductTransfers = $this->getPriceProductTransfers($productViewTransfer);
        $merchantStorageTransfers = $this->getMerchantStorageList($merchantIds);

        foreach ($productOffersStorageTransfers as $productOfferStorageTransfer) {
            if (isset($merchantStorageTransfers[$productOfferStorageTransfer->getIdMerchant()])) {
                $merchantStorageTransfer = $merchantStorageTransfers[$productOfferStorageTransfer->getIdMerchant()];
                $merchantStorageTransfer->setMerchantUrl($this->getResolvedUrl($merchantStorageTransfer, $localeName));
                $productOfferStorageTransfer->setMerchantStorage($merchantStorageTransfer);

                $currentProductPriceTransfer = $this->getCurrentProductPriceTransferForOffer(
                    $priceProductTransfers,
                    $productOfferStorageTransfer->getProductOfferReference()
                );

                $productOfferStorageTransfer->setPrice($currentProductPriceTransfer);

                $productOfferStorageList[] = $productOfferStorageTransfer;
            }
        }

        return $productOfferStorageList;
    }

    /**
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantStorageTransfer[]
     */
    protected function getMerchantStorageList(array $merchantIds): array
    {
        $indexedMerchantStorageTransfers = [];
        
        $merchantStorageTransfers = $this->merchantStorageClient->get($merchantIds);

        foreach ($merchantStorageTransfers as $merchantStorageTransfer) {
            $indexedMerchantStorageTransfers[$merchantStorageTransfer->getIdMerchant()] = $merchantStorageTransfer;
        }

        return $indexedMerchantStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantStorageTransfer $merchantStorageTransfer
     * @param string $localeName
     *
     * @return string
     */
    protected function getResolvedUrl(MerchantStorageTransfer $merchantStorageTransfer, string $localeName): string
    {
        $locale = strstr($localeName, '_', true);

        foreach ($merchantStorageTransfer->getUrlCollection() as $urlTransfer) {
            $urlLocale = mb_substr($urlTransfer->getUrl(), 1, 2);
            if ($locale === $urlLocale) {
                return $urlTransfer->getUrl();
            }
        }

        return '';
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return \Generated\Shared\Transfer\PriceProductTransfer[]
     */
    protected function getPriceProductTransfers(ProductViewTransfer $productViewTransfer): array
    {
        return $this->priceProductStorageClient->getResolvedPriceProductConcreteTransfers(
            $productViewTransfer->getIdProductConcrete(),
            $productViewTransfer->getIdProductAbstract()
        );
    }

    /**
     * @param \Generated\Shared\Transfer\PriceProductTransfer[] $priceProductTransfers
     * @param string $productOfferReference
     *
     * @return \Generated\Shared\Transfer\CurrentProductPriceTransfer
     */
    protected function getCurrentProductPriceTransferForOffer(array $priceProductTransfers, string $productOfferReference): CurrentProductPriceTransfer
    {
        $offerPriceProductTransfers = [];

        foreach ($priceProductTransfers as $priceProductTransfer) {
            if (!$this->isCurrentProductOffer($productOfferReference, $priceProductTransfer)) {
                continue;
            }
            $offerPriceProductTransfers[] = $priceProductTransfer;
        }
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())->setProductOfferReference($productOfferReference);

        return $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter($offerPriceProductTransfers, $priceProductFilterTransfer);
    }

    /**
     * @param string $productOfferReference
     * @param \Generated\Shared\Transfer\PriceProductTransfer $priceProductTransfer
     *
     * @return bool
     */
    protected function isCurrentProductOffer(string $productOfferReference, PriceProductTransfer $priceProductTransfer): bool
    {
        $dimensionType = $priceProductTransfer->getPriceDimension()->getType();
        $priceDimensionProductOfferReference = $priceProductTransfer->getPriceDimension()->getProductOfferReference();

        if ($dimensionType === MerchantProductOfferWidgetConfig::DIMENSION_TYPE_PRODUCT_OFFER && $priceDimensionProductOfferReference !== $productOfferReference) {
            return false;
        }

        return true;
    }
}
