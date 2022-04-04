<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductWidget\Expander;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReaderInterface;
use SprykerShop\Yves\MerchantProductWidget\Resolver\ShopContextResolverInterface;

class MerchantProductOfferCollectionExpander implements MerchantProductOfferCollectionExpanderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReaderInterface
     */
    protected $merchantProductReader;

    /**
     * @var \SprykerShop\Yves\MerchantProductWidget\Resolver\ShopContextResolverInterface
     */
    private $shopContextResolver;

    /**
     * @param \SprykerShop\Yves\MerchantProductWidget\Dependency\Client\MerchantProductWidgetToMerchantStorageClientInterface $merchantStorageClient
     * @param \SprykerShop\Yves\MerchantProductWidget\Reader\MerchantProductReaderInterface $merchantProductReader
     * @param \SprykerShop\Yves\MerchantProductWidget\Resolver\ShopContextResolverInterface $shopContextResolver
     */
    public function __construct(
        MerchantProductWidgetToMerchantStorageClientInterface $merchantStorageClient,
        MerchantProductReaderInterface $merchantProductReader,
        ShopContextResolverInterface $shopContextResolver
    ) {
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantProductReader = $merchantProductReader;
        $this->shopContextResolver = $shopContextResolver;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     * @param string $locale
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    public function expand(array $productOfferTransfers, string $locale): array
    {
        $sku = $this->findSkuFromProductOfferTransfers($productOfferTransfers);

        if (!$sku) {
            return $productOfferTransfers;
        }

        $merchantReference = $this->merchantProductReader->findMerchantReferenceByConcreteSku($sku, $locale);

        if (!$merchantReference) {
            return $productOfferTransfers;
        }

        $shopContextTransfer = $this->shopContextResolver->resolve();

        if ($shopContextTransfer->getMerchantReference() && $merchantReference !== $shopContextTransfer->getMerchantReference()) {
            return $productOfferTransfers;
        }

        $merchantStorageTransfer = $this->merchantStorageClient->findOne(
            (new MerchantStorageCriteriaTransfer())
                ->setMerchantReferences([$merchantReference]),
        );

        if ($merchantStorageTransfer) {
            array_unshift(
                $productOfferTransfers,
                (new ProductOfferTransfer())
                    ->setMerchantName($merchantStorageTransfer->getNameOrFail())
                    ->setMerchantReference($merchantStorageTransfer->getMerchantReferenceOrFail())
                    ->setProductOfferReference(''),
            );
        }

        return $productOfferTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return string|null
     */
    protected function findSkuFromProductOfferTransfers(
        array $productOfferTransfers
    ): ?string {
        foreach ($productOfferTransfers as $productOfferTransfer) {
            if ($productOfferTransfer->getConcreteSku()) {
                return $productOfferTransfer->getConcreteSku();
            }
        }

        return null;
    }
}
