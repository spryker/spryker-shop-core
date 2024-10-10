<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Reader;

use Generated\Shared\Transfer\MerchantProductViewTransfer;
use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
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
     * @var string
     */
    protected const ID_PRODUCT_ABSTRACT = 'id_product_abstract';

    /**
     * @var string
     */
    protected const PRODUCT_CONCRETE_MAPPING_TYPE = 'sku';

    /**
     * @var string
     */
    protected const PARAM_MERCHANT_REFERENCE = 'merchant_reference';

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
        if (!$productViewTransfer->getIdProductConcrete()) {
            return null;
        }

        /** @var int $idProductAbstract */
        $idProductAbstract = $productViewTransfer->getIdProductAbstract();
        $productAbstractStorageData = $this->productStorageClient->findProductAbstractStorageData(
            $idProductAbstract,
            $localeName,
        );

        if (!$productAbstractStorageData) {
            return null;
        }

        $priceProductTransfers = $this->getPriceProductTransfers($productViewTransfer);
        $priceProductFilterTransfer = (new PriceProductFilterTransfer())
            ->setQuantity($productViewTransfer->getQuantity());

        $currentProductPriceTransfer = $this->priceProductClient->resolveProductPriceTransferByPriceProductFilter(
            $priceProductTransfers,
            $priceProductFilterTransfer,
        );

        $merchantProductViewTransfer = $this->merchantProductWidgetMapper->mapProductAbstractStorageDataToMerchantProductViewTransfer(
            $productAbstractStorageData,
            new MerchantProductViewTransfer(),
        );

        /** @var string $merchantReference */
        $merchantReference = $merchantProductViewTransfer->getMerchantReference();
        if (!$merchantReference) {
            return null;
        }

        $merchantStorageTransfer = $this->merchantStorageClient->findOne(
            (new MerchantStorageCriteriaTransfer())->addMerchantReference($merchantReference),
        );

        if (!$merchantStorageTransfer) {
            return null;
        }
        $merchantProductViewTransfer->setMerchantUrl($this->getResolvedUrl($merchantStorageTransfer, $localeName));
        $merchantProductViewTransfer->setMerchantName($merchantStorageTransfer->getName());
        $merchantProductViewTransfer->setPrice($currentProductPriceTransfer);

        return $merchantProductViewTransfer;
    }

    /**
     * @param string $sku
     * @param string $locale
     *
     * @return string|null
     */
    public function findMerchantReferenceByConcreteSku(string $sku, string $locale): ?string
    {
        $productConcreteStorageData = $this->productStorageClient
            ->findProductConcreteStorageDataByMapping(
                static::PRODUCT_CONCRETE_MAPPING_TYPE,
                $sku,
                $locale,
            );

        if (!isset($productConcreteStorageData[static::ID_PRODUCT_ABSTRACT])) {
            return null;
        }

        $productAbstractStorageData = $this->productStorageClient
            ->findProductAbstractStorageData(
                $productConcreteStorageData[static::ID_PRODUCT_ABSTRACT],
                $locale,
            );

        if ($productAbstractStorageData === null || !array_key_exists(static::PARAM_MERCHANT_REFERENCE, $productAbstractStorageData)) {
            return null;
        }

        return $productAbstractStorageData[static::PARAM_MERCHANT_REFERENCE];
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

    /**
     * @param \Generated\Shared\Transfer\ProductViewTransfer $productViewTransfer
     *
     * @return array<\Generated\Shared\Transfer\PriceProductTransfer>
     */
    protected function getPriceProductTransfers(ProductViewTransfer $productViewTransfer): array
    {
        /** @var int $idProductConcrete */
        $idProductConcrete = $productViewTransfer->getIdProductConcrete();
        /** @var int $idProductAbstract */
        $idProductAbstract = $productViewTransfer->getIdProductAbstract();

        return $this->priceProductStorageClient->getResolvedPriceProductConcreteTransfers(
            $idProductConcrete,
            $idProductAbstract,
        );
    }
}
