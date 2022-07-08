<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget\Form\DataProvider;

use Generated\Shared\Transfer\MerchantStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer;
use Generated\Shared\Transfer\ProductOfferStorageCriteriaTransfer;
use Generated\Shared\Transfer\ProductOfferTransfer;
use Generated\Shared\Transfer\ShopContextTransfer;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface;

class MerchantProductOffersSelectFormDataProvider
{
    /**
     * @uses \SprykerShop\Yves\MerchantProductOfferWidget\Form\MerchantProductOffersSelectForm::FIELD_PRODUCT_OFFER_REFERENCE
     *
     * @var string
     */
    protected const FIELD_PRODUCT_OFFER_REFERENCE = 'product_offer_reference';

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface
     */
    protected $shopContextResolver;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface
     */
    protected $productOfferStorageClient;

    /**
     * @var \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface
     */
    protected $merchantStorageClient;

    /**
     * @var array<\SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin\MerchantProductOfferCollectionExpanderPluginInterface>
     */
    protected $merchantProductOfferCollectionExpanderPlugins;

    /**
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface $shopContextResolver
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface $productOfferStorageClient
     * @param \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface $merchantStorageClient
     * @param array<\SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin\MerchantProductOfferCollectionExpanderPluginInterface> $merchantProductOfferCollectionExpanderPlugins
     */
    public function __construct(
        ShopContextResolverInterface $shopContextResolver,
        MerchantProductOfferWidgetToProductOfferStorageClientInterface $productOfferStorageClient,
        MerchantProductOfferWidgetToMerchantStorageClientInterface $merchantStorageClient,
        array $merchantProductOfferCollectionExpanderPlugins
    ) {
        $this->shopContextResolver = $shopContextResolver;
        $this->productOfferStorageClient = $productOfferStorageClient;
        $this->merchantStorageClient = $merchantStorageClient;
        $this->merchantProductOfferCollectionExpanderPlugins = $merchantProductOfferCollectionExpanderPlugins;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        $shopContextTransfer = $this->shopContextResolver->resolve();

        return $shopContextTransfer->getMerchantReference() === null ? null : [
            static::FIELD_PRODUCT_OFFER_REFERENCE => $shopContextTransfer->getMerchantReference(),
        ];
    }

    /**
     * @param string $sku
     * @param string|null $merchantReference
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    public function getOptions(string $sku, ?string $merchantReference = null): array
    {
        $productOfferStorageCollectionTransfer = $this->getProductOffersBySku($sku);
        $merchantStorageTransfers = $this->getMerchants($productOfferStorageCollectionTransfer);
        $merchantNamesIndexedByMerchantReferences = $this->getMerchantNamesIndexedByMerchantReferences($merchantStorageTransfers);
        $shopContextTransfer = $this->shopContextResolver->resolve();

        $productOfferTransfers = $this->getProductOffers(
            $productOfferStorageCollectionTransfer,
            $shopContextTransfer,
            $merchantNamesIndexedByMerchantReferences,
            $sku,
        );

        foreach ($this->merchantProductOfferCollectionExpanderPlugins as $merchantProductOfferCollectionExpanderPlugin) {
            $productOfferTransfers = $merchantProductOfferCollectionExpanderPlugin->expand($productOfferTransfers);
        }

        $productOfferTransfers = $this->getProductOffersWithMerchantReference($productOfferTransfers);

        if ($merchantReference) {
            $productOfferTransfers = $this->filterProductOffersByMerchantReference($productOfferTransfers, $merchantReference);
        }

        return $productOfferTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     *
     * @return array<\Generated\Shared\Transfer\MerchantStorageTransfer>
     */
    protected function getMerchants(
        ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
    ): array {
        $merchantStorageCriteriaTransfer = new MerchantStorageCriteriaTransfer();

        foreach ($productOfferStorageCollectionTransfer->getProductOffers() as $productOfferTransfer) {
            if ($productOfferTransfer->getMerchantReference()) {
                $merchantStorageCriteriaTransfer->addMerchantReference($productOfferTransfer->getMerchantReference());
            }
        }

        return $this->merchantStorageClient->get($merchantStorageCriteriaTransfer);
    }

    /**
     * @param string $sku
     *
     * @return \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer
     */
    protected function getProductOffersBySku(string $sku): ProductOfferStorageCollectionTransfer
    {
        $productOfferStorageCriteriaTransfer = (new ProductOfferStorageCriteriaTransfer())
            ->addProductConcreteSku($sku);

        return $this->productOfferStorageClient->getProductOfferStoragesBySkus($productOfferStorageCriteriaTransfer);
    }

    /**
     * @param array<\Generated\Shared\Transfer\MerchantStorageTransfer> $merchantStorageTransfers
     *
     * @return array<string, string>
     */
    protected function getMerchantNamesIndexedByMerchantReferences(array $merchantStorageTransfers): array
    {
        $merchantNamesIndexedByMerchantReferences = [];

        foreach ($merchantStorageTransfers as $merchantStorageTransfer) {
            $merchantReference = $merchantStorageTransfer->getMerchantReferenceOrFail();
            $merchantName = $merchantStorageTransfer->getNameOrFail();

            $merchantNamesIndexedByMerchantReferences[$merchantReference] = $merchantName;
        }

        return $merchantNamesIndexedByMerchantReferences;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer
     * @param \Generated\Shared\Transfer\ShopContextTransfer $shopContextTransfer
     * @param array<string, string> $merchantNamesIndexedByMerchantReferences
     * @param string $sku
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function getProductOffers(
        ProductOfferStorageCollectionTransfer $productOfferStorageCollectionTransfer,
        ShopContextTransfer $shopContextTransfer,
        array $merchantNamesIndexedByMerchantReferences,
        string $sku
    ): array {
        $productOfferTransfers = [
            (new ProductOfferTransfer())->setConcreteSku($sku),
        ];

        foreach ($productOfferStorageCollectionTransfer->getProductOffers() as $productOfferStorageTransfer) {
            $merchantReference = $productOfferStorageTransfer->getMerchantReferenceOrFail();

            if ($shopContextTransfer->getMerchantReference() && $merchantReference !== $shopContextTransfer->getMerchantReference()) {
                continue;
            }

            $productOfferTransfers[] = (new ProductOfferTransfer())
                ->setConcreteSku($productOfferStorageTransfer->getProductConcreteSkuOrFail())
                ->setMerchantName($merchantNamesIndexedByMerchantReferences[$merchantReference])
                ->setMerchantReference($merchantReference)
                ->setProductOfferReference($productOfferStorageTransfer->getProductOfferReferenceOrFail());
        }

        return $productOfferTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     * @param string $merchantReference
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function filterProductOffersByMerchantReference(array $productOfferTransfers, string $merchantReference): array
    {
        foreach ($productOfferTransfers as $key => $productOfferTransfer) {
            if ($productOfferTransfer->getMerchantReference() === $merchantReference) {
                continue;
            }

            unset($productOfferTransfers[$key]);
        }

        return $productOfferTransfers;
    }

    /**
     * @param array<\Generated\Shared\Transfer\ProductOfferTransfer> $productOfferTransfers
     *
     * @return array<\Generated\Shared\Transfer\ProductOfferTransfer>
     */
    protected function getProductOffersWithMerchantReference(array $productOfferTransfers): array
    {
        return array_filter(
            $productOfferTransfers,
            function ($productOfferTransfer) {
                return $productOfferTransfer->getMerchantReference();
            },
        );
    }
}
