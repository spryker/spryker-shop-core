<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Reader;

use Generated\Shared\Transfer\MerchantProductViewTransfer;
use Generated\Shared\Transfer\MerchantStorageTransfer;
use Generated\Shared\Transfer\PriceProductFilterTransfer;
use Generated\Shared\Transfer\ProductViewTransfer;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToPriceProductClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToPriceProductStorageClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToProductStorageClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Mapper\MerchantProductMapper;

class MerchantProductReader implements MerchantProductReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToProductStorageClientInterface
     */
    protected $productStorageClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToPriceProductClientInterface
     */
    protected $priceProductClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToPriceProductStorageClientInterface
     */
    protected $priceProductStorageClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductWidget\Mapper\MerchantProductMapper
     */
    protected $merchantProductWidgetMapper;

    /**
     * @param \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToProductStorageClientInterface $productStorageClient
     * @param \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToPriceProductClientInterface $priceProductClient
     * @param \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToPriceProductStorageClientInterface $priceProductStorageClient
     * @param \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantStorageClientInterface $merchantStorageClient
     * @param \SprykerShop\Yves\MerchantProductWidget\Mapper\MerchantProductMapper $merchantProductWidgetMapper
     */
    public function __construct(
        MerchantProductWidgetToProductStorageClientInterface $productStorageClient,
        MerchantProductWidgetToPriceProductClientInterface $priceProductClient,
        MerchantProductWidgetToPriceProductStorageClientInterface $priceProductStorageClient,
        MerchantProductWidgetToMerchantStorageClientInterface $merchantStorageClient,
        MerchantProductMapper $merchantProductWidgetMapper
    ) {
        $this->productStorageClient = $productStorageClient;
        $this->priceProductClient = $priceProductClient;
        $this->priceProductStorageClient = $priceProductStorageClient;
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantProductWidgetMapper = $merchantProductWidgetMapper;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     * @param string $localeName
     *
     * @return \Generated\Shared\Transfer\MerchantProductViewTransfer|null
     */
    public function findMerchantProductView(ProductViewTransfer $productViewTransfer, string $localeName): ?MerchantProductViewTransfer
    {
        if (!$productViewTransfer->getIdProductAbstract()) {
            return null;
        }

        $productAbstractStorageData = $this->productStorageClient->findProductAbstractStorageData(
            $productViewTransfer->getIdProductAbstract(),
            $localeName
        );

        if (!$productAbstractStorageData) {
            return null;
        }

        $priceProductTransfers = $this->getPriceProductTransfers($productViewTransfer);
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($productViewTransfer->getQuantity());

        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter(
            $priceProductTransfers,
            $priceProductFilterTransfer
        );

        $merchantProductViewTransfer = $this->merchantProductWidgetMapper->mapProductAbstractStorageDataToMerchantProductViewTransfer(
            $productAbstractStorageData,
            new MerchantProductViewTransfer()
        );
        $merchantStorageTransfer = $this->merchantStorageClient->findOneByMerchantReference($merchantProductViewTransfer->getMerchantReference());

        if (!$merchantStorageTransfer) {
            return null;
        }
        $merchantProductViewTransfer->setMerchantUrl($this->getResolvedUrl($merchantStorageTransfer, $localeName));
        $merchantProductViewTransfer->setMerchantName($merchantStorageTransfer->getName());
        $merchantProductViewTransfer->setPrice($currentProductPriceTransfer);

        return $merchantProductViewTransfer;
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
}
