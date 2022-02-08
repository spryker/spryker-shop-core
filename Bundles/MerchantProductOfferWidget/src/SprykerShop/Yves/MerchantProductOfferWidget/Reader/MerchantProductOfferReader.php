<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Reader;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface;
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
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface $shopContextResolver
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface $merchantStorageClient
     */
    public function __construct(
        MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient,
        ShopContextResolverInterface $shopContextResolver,
        MerchantProductOfferWidgetToMerchantStorageClientInterface $merchantStorageClient
    ) {
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
        $this->shopContextResolver = $shopContextResolver;
        $this->merchantStorageClient = $merchantStorageClient;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferStorageTransfer>
     */
    public function getProductOffers(ProductViewTransfer $productViewTransfer, string $localeName): array
    {
        if (!$productViewTransfer->getIdProductConcrete()) {
            return [];
        }

        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->fromArray($this->shopContextResolver->resolve()->modifiedToArray(), true);

        /** @var string $sku */
        $sku = $productViewTransfer->getSku();
        $productOfferStorageCriteriaTransfer->addProductConcreteSku($sku);

        $productOfferStorageCollectionTransfer = $this->merchantProductOfferStorageClient->getProductOffersBySkus($productOfferStorageCriteriaTransfer);
        /** @var array<\Generated\Shared\Transfer\ProductOfferStorageTransfer> $productOffers */
        $productOffers = $productOfferStorageCollectionTransfer->getProductOffers()->getArrayCopy();

        foreach ($productOffers as $productOffer) {
            $merchantStorageTransfer = $productOffer->getMerchantStorageOrFail();
            $merchantStorageTransfer->setMerchantUrl($this->getResolvedUrl($merchantStorageTransfer, $localeName));
        }

        return $productOffers;
    }

    /**
     * @param string $productOfferReference
     *
     * @return string|null
     */
    public function findMerchantReferenceByProductOfferReference(string $productOfferReference): ?string
    {
        $productOfferStorageTransfer = $this->merchantProductOfferStorageClient
            ->findProductOfferStorageByReference($productOfferReference);

        if (!$productOfferStorageTransfer) {
            return null;
        }

        $merchantStorageTransfer = $this->merchantStorageClient->findOne(
            (new MerchantStorageCriteriaTransfer())->addMerchantReference(
                $productOfferStorageTransfer->getMerchantReferenceOrFail(),
            ),
        );

        if (!$merchantStorageTransfer) {
            return null;
        }

        return $merchantStorageTransfer->getMerchantReference();
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
            /** @var string $url */
            $url = $urlTransfer->getUrl();

            $urlLocale = mb_substr($url, 1, 2);
            if ($locale === $urlLocale) {
                return $url;
            }
        }

        return '';
    }
}
