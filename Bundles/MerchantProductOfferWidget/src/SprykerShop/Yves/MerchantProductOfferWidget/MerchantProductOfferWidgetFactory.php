<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantProductOfferWidget;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Expander\MerchantProductOfferExpander;
use SprykerShop\Yves\MerchantProductOfferWidget\Expander\MerchantProductOfferExpanderInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Expander\QuickOrder\MerchantProductOfferQuickOrderFormExpander;
use SprykerShop\Yves\MerchantProductOfferWidget\Expander\QuickOrder\MerchantProductOfferQuickOrderFormExpanderInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Expander\QuickOrder\MerchantProductOfferQuickOrderItemExpander;
use SprykerShop\Yves\MerchantProductOfferWidget\Expander\QuickOrder\MerchantProductOfferQuickOrderItemExpanderInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Form\DataProvider\MerchantProductOffersSelectFormDataProvider;
use SprykerShop\Yves\MerchantProductOfferWidget\Form\MerchantProductOffersSelectForm;
use SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReader;
use SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolver;
use SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormInterface;

class MerchantProductOfferWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Reader\MerchantProductOfferReaderInterface
     */
    public function createProductOfferReader(): MerchantProductOfferReaderInterface
    {
        return new MerchantProductOfferReader(
            $this->getProductOfferStorageClient(),
            $this->createShopContextResolver(),
            $this->getMerchantStorageClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Expander\MerchantProductOfferExpanderInterface
     */
    public function createMerchantProductOfferExpander(): MerchantProductOfferExpanderInterface
    {
        return new MerchantProductOfferExpander(
            $this->createProductOfferReader(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Resolver\ShopContextResolverInterface
     */
    public function createShopContextResolver(): ShopContextResolverInterface
    {
        return new ShopContextResolver($this->getContainer());
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Expander\QuickOrder\MerchantProductOfferQuickOrderFormExpanderInterface
     */
    public function createMerchantProductOfferQuickOrderFormExpander(): MerchantProductOfferQuickOrderFormExpanderInterface
    {
        return new MerchantProductOfferQuickOrderFormExpander(
            $this->createMerchantProductOffersSelectFormDataProvider(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Expander\QuickOrder\MerchantProductOfferQuickOrderItemExpanderInterface
     */
    public function createMerchantProductOfferQuickOrderItemExpander(): MerchantProductOfferQuickOrderItemExpanderInterface
    {
        return new MerchantProductOfferQuickOrderItemExpander(
            $this->getProductOfferStorageClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToProductOfferStorageClientInterface
     */
    public function getProductOfferStorageClient(): MerchantProductOfferWidgetToProductOfferStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::CLIENT_PRODUCT_OFFER_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Dependency\Client\MerchantProductOfferWidgetToMerchantStorageClientInterface
     */
    public function getMerchantStorageClient(): MerchantProductOfferWidgetToMerchantStorageClientInterface
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::CLIENT_MERCHANT_STORAGE);
    }

    /**
     * @param array|null $data
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createMerchantProductOffersSelectForm(?array $data, array $options): FormInterface
    {
        return $this->getFormFactory()
            ->create(MerchantProductOffersSelectForm::class, $data, $options);
    }

    /**
     * @return \SprykerShop\Yves\MerchantProductOfferWidget\Form\DataProvider\MerchantProductOffersSelectFormDataProvider
     */
    public function createMerchantProductOffersSelectFormDataProvider(): MerchantProductOffersSelectFormDataProvider
    {
        return new MerchantProductOffersSelectFormDataProvider(
            $this->createShopContextResolver(),
            $this->getProductOfferStorageClient(),
            $this->getMerchantStorageClient(),
            $this->getMerchantProductOfferCollectionExpanderPlugins(),
        );
    }

    /**
     * @return \Symfony\Component\Form\FormFactory
     */
    public function getFormFactory(): FormFactory
    {
        return $this->getProvidedDependency(ApplicationConstants::FORM_FACTORY);
    }

    /**
     * @return array<\SprykerShop\Yves\MerchantProductOfferWidgetExtension\Dependency\Plugin\MerchantProductOfferCollectionExpanderPluginInterface>
     */
    public function getMerchantProductOfferCollectionExpanderPlugins(): array
    {
        return $this->getProvidedDependency(MerchantProductOfferWidgetDependencyProvider::PLUGINS_MERCHANT_PRODUCT_OFFER_COLLECTION_EXPANDER);
    }
}
