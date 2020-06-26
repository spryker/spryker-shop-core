<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Reader;

use Generated\Shared\Transfer\MerchantProductOfferTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Mapper\MerchantProductViewMapper;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface;

class MerchantProductOfferReader implements MerchantProductOfferReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface
     */
    protected $shopContextResolver;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Mapper\MerchantProductViewMapper
     */
    protected $merchantProductOfferMapper;

    /**
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface $shopContextResolver
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Mapper\MerchantProductViewMapper $merchantProductOfferMapper
     */
    public function __construct(
        MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient,
        ShopContextResolverInterface $shopContextResolver,
        MerchantProductViewMapper $merchantProductOfferMapper
    ) {
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
        $this->shopContextResolver = $shopContextResolver;
        $this->merchantProductOfferMapper = $merchantProductOfferMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantProductOfferTransfer[]
     */
    public function getProductOfferCollection(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return [];
        }

        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->fromArray($this->shopContextResolver->resolve()->modifiedToArray(), true);
        $productOfferStorageCriteriaTransfer->addProductConcreteSku($productViewTransfer->getSku());

        $productOfferStorageCollectionTransfer = $this->merchantProductOfferStorageClient->getProductOffersBySkus($productOfferStorageCriteriaTransfer);
        $productOffersStorageTransfers = $productOfferStorageCollectionTransfer->getProductOffersStorage();

        $merchantProductOfferTransfers = [];

        foreach ($productOffersStorageTransfers as $productOfferStorageTransfer) {
            $merchantStorageTransfer = $productOfferStorageTransfer->getMerchantStorage();
            $merchantProductOfferTransfer = $this->merchantProductOfferMapper
                ->mapProductOfferStorageTransferToMerchantProductOfferTransfer($productOfferStorageTransfer, new MerchantProductOfferTransfer());

            $merchantProductOfferTransfer->setMerchantUrl($this->getResolvedUrl($merchantStorageTransfer, $localeName));

            $merchantProductOfferTransfers[] = $merchantProductOfferTransfer;
        }

        return $merchantProductOfferTransfers;
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
}
