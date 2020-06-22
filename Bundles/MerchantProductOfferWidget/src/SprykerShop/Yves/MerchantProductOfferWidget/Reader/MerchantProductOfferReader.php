<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Reader;

use Generated\Shared\Transfer\CurrentProductPriceTransfer;
use Generated\Shared\Transfer\MerchantProductViewCollectionTransfer;
use Generated\Shared\Transfer\MerchantProductViewTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\PriceProductTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Shared\MerchantProductOfferWidget\MerchantProductOfferWidgetConfig;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToPriceProductStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Service\MerchantProductOfferWidgetToPriceProductClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Mapper\MerchantProductViewMapper;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Sorter\MerchantProductViewCollectionSorterInterface;

class MerchantProductOfferReader implements MerchantProductOfferReaderInterface
{
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
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Mapper\MerchantProductViewMapper
     */
    protected $merchantProductOfferMapper;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin\MerchantProductViewCollectionExpanderPluginInterface[]
     */
    protected $merchantProductViewCollectionExpanderPlugins;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Sorter\MerchantProductViewCollectionSorterInterface
     */
    protected $merchantProductSorter;

    /**
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Service\MerchantProductOfferWidgetToPriceProductClientInterface $priceProductClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface $shopContextResolver
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Mapper\MerchantProductViewMapper $merchantProductOfferMapper
     * @param \SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin\MerchantProductViewCollectionExpanderPluginInterface[] $merchantProductViewCollectionExpanderPlugins
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Sorter\MerchantProductViewCollectionSorterInterface $merchantProductSorter
     */
    public function __construct(
        MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient,
        MerchantProductOfferWidgetToPriceProductClientInterface $priceProductClient,
        MerchantProductOfferWidgetToPriceProductStorageClientInterface $priceProductStorageClient,
        ShopContextResolverInterface $shopContextResolver,
        MerchantProductViewMapper $merchantProductOfferMapper,
        array $merchantProductViewCollectionExpanderPlugins,
        MerchantProductViewCollectionSorterInterface $merchantProductSorter
    ) {
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
        $this->priceProductClient = $priceProductClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->shopContextResolver = $shopContextResolver;
        $this->merchantProductOfferMapper = $merchantProductOfferMapper;
        $this->merchantProductViewCollectionExpanderPlugins = $merchantProductViewCollectionExpanderPlugins;
        $this->merchantProductSorter = $merchantProductSorter;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantProductViewCollectionTransfer
     */
    public function getMerchantProductViewCollection(ProductViewTransfer $productViewTransfer, string $localeName): MerchantProductViewCollectionTransfer
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return [];
        }
        $merchantProductViewCollectionTransfer = new MerchantProductViewCollectionTransfer();

        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->fromArray($this->shopContextResolver->resolve()->modifiedToArray(), true);
        $productOfferStorageCriteriaTransfer->addProductConcreteSku($productViewTransfer->getSku());

        $productOfferStorageCollectionTransfer = $this->merchantProductOfferStorageClient->getProductOffersBySkus($productOfferStorageCriteriaTransfer);
        $productOffersStorageTransfers = $productOfferStorageCollectionTransfer->getProductOffersStorage();

        $priceProductTransfers = $this->getPriceProductTransfers($productViewTransfer);

        foreach ($productOffersStorageTransfers as $productOfferStorageTransfer) {
            $merchantStorageTransfer = $productOfferStorageTransfer->getMerchantStorage();
            $merchantProductViewTransfer = $this->merchantProductOfferMapper
                ->mapProductOfferStorageTransferToMerchantProductViewTransfer($productOfferStorageTransfer, new MerchantProductViewTransfer());

            $merchantProductViewTransfer->setMerchantUrl($this->getResolvedUrl($merchantStorageTransfer, $localeName));

            $currentProductPriceTransfer = $this->getCurrentProductPriceTransferForOffer(
                $priceProductTransfers,
                $productOfferStorageTransfer->getProductOfferReference()
            );

            $merchantProductViewTransfer->setPrice($currentProductPriceTransfer);

            $merchantProductViewCollectionTransfer->addMerchantProductView($merchantProductViewTransfer);
        }

        $externalMerchantProductViewCollectionTransfer = new MerchantProductViewCollectionTransfer();

        foreach ($this->merchantProductViewCollectionExpanderPlugins as $merchantProductViewCollectionExpanderPlugin) {
            $externalMerchantProductViewCollectionTransfer = $merchantProductViewCollectionExpanderPlugin->expand(
                $externalMerchantProductViewCollectionTransfer,
                $productViewTransfer
            );
        }

        $merchantProductViewCollectionTransfer = $this->mergeMerchantProductTransfers(
            $merchantProductViewCollectionTransfer,
            $externalMerchantProductViewCollectionTransfer
        );

        return $this->merchantProductSorter->sort($merchantProductViewCollectionTransfer);
    }

    protected function mergeMerchantProductTransfers(
        MerchantProductViewCollectionTransfer $merchantProductTransfer,
        MerchantProductViewCollectionTransfer $externalMerchantProductTransfer
    ): MerchantProductViewCollectionTransfer {
        foreach ($externalMerchantProductTransfer->getMerchantProductViews() as $externalMerchantProductViewTransfer) {
            foreach ($merchantProductTransfer->getMerchantProductViews() as $key => $merchantProductViewTransfer) {
                if ($externalMerchantProductViewTransfer->getMerchantReference() !== $merchantProductViewTransfer->getMerchantReference()
                ) {
                    $merchantProductTransfer->addMerchantProductView($externalMerchantProductViewTransfer);
                }
            }
        }

        return $merchantProductTransfer;
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
