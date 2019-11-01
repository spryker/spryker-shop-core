<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Reader;

use Generated\Shared\Transfer\MerchantProfileStorageTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantProfileStorageClientInterface;

class ProductOfferReader implements ProductOfferReaderInterface
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
        $productOfferStorageCollection = [];
        $productOfferStorageCollectionTransfers = $this->merchantProductOfferStorageClient->getProductOfferStorageCollection($productViewTransfer->getSku());
        foreach ($productOfferStorageCollectionTransfers as $productOfferStorageTransfer) {
            $merchantProfileStorageTransfer = $this->merchantProfileStorageClient->findMerchantProfileStorageData($productOfferStorageTransfer->getIdMerchant());
            if ($merchantProfileStorageTransfer) {
                $merchantProfileStorageTransfer->setMerchantUrl($this->getResolvedUrl($merchantProfileStorageTransfer, $localeName));
                $productOfferStorageTransfer->setMerchantProfile($merchantProfileStorageTransfer);
                $productOfferStorageCollection[] = $productOfferStorageTransfer;
            }
        }

        return $productOfferStorageCollection;
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
