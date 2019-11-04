<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Reader;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;
use Generated\Shared\Transfer\ProductOfferStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProfileStorageClientInterface;

class MerchantProductOfferReader implements MerchantProductOfferReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProfileStorageClientInterface
     */
    protected $merchantProfileStorageClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface
     */
    protected $merchantProductOfferStorageClient;

    /**
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProfileStorageClientInterface $merchantProfileStorageClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
     */
    public function __construct(
        MerchantProductOfferWidgetToMerchantProfileStorageClientInterface $merchantProfileStorageClient,
        MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface $merchantProductOfferStorageClient
    ) {
        $this->merchantProfileStorageClient = $merchantProfileStorageClient;
        $this->merchantProductOfferStorageClient = $merchantProductOfferStorageClient;
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
        $productOfferStorageCollection = $this->merchantProductOfferStorageClient->getProductOfferStorageCollection($productViewTransfer->getSku());
        $productOffersStorageTransfers = $productOfferStorageCollection->getProductOffersStorage();
        $merchantIds = array_map(function (ProductOfferStorageTransfer $productOffersStorageTransfer) {
            return $productOffersStorageTransfer->getIdMerchant();
        }, $productOffersStorageTransfers->getArrayCopy());

        $merchantProfileStorageTransfers = $this->getMerchantProfileStorageList($merchantIds);

        foreach ($productOffersStorageTransfers as $productOfferStorageTransfer) {
            if (isset($merchantProfileStorageTransfers[$productOfferStorageTransfer->getIdMerchant()])) {
                $merchantProfileStorageTransfer = $merchantProfileStorageTransfers[$productOfferStorageTransfer->getIdMerchant()];
                $merchantProfileStorageTransfer->setMerchantUrl($this->getResolvedUrl($merchantProfileStorageTransfer, $localeName));
                $productOfferStorageTransfer->setMerchantProfile($merchantProfileStorageTransfer);
                $productOfferStorageList[] = $productOfferStorageTransfer;
            }
        }

        return $productOfferStorageList;
    }

    /**
     * @param int[] $merchantIds
     *
     * @return \Generated\Shared\Transfer\MerchantProfileStorageTransfer[]
     */
    protected function getMerchantProfileStorageList($merchantIds): array
    {
        $indexedMerchantProfileStorageTransfers = [];
        
        $merchantProfileStorageTransfers = $this->merchantProfileStorageClient->findMerchantProfileStorageList($merchantIds);

        foreach ($merchantProfileStorageTransfers as $merchantProfileStorageTransfer) {
            $indexedMerchantProfileStorageTransfers[$merchantProfileStorageTransfer->getFkMerchant()] = $merchantProfileStorageTransfer;
        }

        return $indexedMerchantProfileStorageTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantProfileStorageTransfer $merchantProfileStorageTransfer
     * @param string $localeName
     *
     * @return string
     */
    protected function getResolvedUrl(MerchantProfileStorageTransfer $merchantProfileStorageTransfer, string $localeName): string
    {
        $locale = strstr($localeName, '_', true);

        foreach ($merchantProfileStorageTransfer->getUrlCollection() as $merchantProfileStorageUrlTransfer) {
            $urlLocale = mb_substr($merchantProfileStorageUrlTransfer->getUrl(), 1, 2);
            if ($locale === $urlLocale) {
                return $merchantProfileStorageUrlTransfer->getUrl();
            }
        }

        return '';
    }
}
