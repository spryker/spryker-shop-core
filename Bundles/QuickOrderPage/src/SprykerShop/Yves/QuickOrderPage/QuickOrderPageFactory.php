<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToMessengerClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToSearchClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToStoreClientInterface;
use SprykerShop\Yves\QuickOrderPage\Form\DataProvider\SearchFieldChoicesDataProvider;
use SprykerShop\Yves\QuickOrderPage\Form\DataProvider\SearchFieldChoicesDataProviderInterface;
use SprykerShop\Yves\QuickOrderPage\Form\FormFactory;
use SprykerShop\Yves\QuickOrderPage\Form\Handler\QuickOrderFormOperationHandler;
use SprykerShop\Yves\QuickOrderPage\Form\Handler\QuickOrderFormOperationHandlerInterface;
use SprykerShop\Yves\QuickOrderPage\Model\ProductFinder;
use SprykerShop\Yves\QuickOrderPage\Model\ProductFinderInterface;
use SprykerShop\Yves\QuickOrderPage\Model\SuggestionDataProvider;
use SprykerShop\Yves\QuickOrderPage\Model\SuggestionDataProviderInterface;
use SprykerShop\Yves\QuickOrderPage\Model\TextOrderParser;
use SprykerShop\Yves\QuickOrderPage\Model\TextOrderParserInterface;

/**
 * @method \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig getConfig()
 */
class QuickOrderPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\QuickOrderPage\QuickOrderPageConfig
     */
    public function getBundleConfig(): QuickOrderPageConfig
    {
        return $this->getConfig();
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\FormFactory
     */
    public function createQuickOrderFormFactory(): FormFactory
    {
        return new FormFactory();
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\Handler\QuickOrderFormOperationHandlerInterface
     */
    public function createFormOperationHandler(): QuickOrderFormOperationHandlerInterface
    {
        return new QuickOrderFormOperationHandler($this->getCartClient(), $this->getMessengerClient());
    }

    /**
     * @param string $textOrder
     *
     * @return \SprykerShop\Yves\QuickOrderPage\Model\TextOrderParserInterface
     */
    public function createTextOrderParser(string $textOrder): TextOrderParserInterface
    {
        return new TextOrderParser($textOrder, $this->getConfig(), $this->createProductFinder());
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\DataProvider\SearchFieldChoicesDataProviderInterface
     */
    public function createSearchFieldChoicesDataProvider(): SearchFieldChoicesDataProviderInterface
    {
        return new SearchFieldChoicesDataProvider();
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Model\ProductFinderInterface
     */
    public function createProductFinder(): ProductFinderInterface
    {
        return new ProductFinder(
            $this->getStoreClient(),
            $this->getSearchClient(),
            $this->getProductStorageClient(),
            $this->getLocaleClient(),
            $this->getBundleConfig()
        );
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Model\SuggestionDataProviderInterface
     */
    public function createSuggestionDataProvider(): SuggestionDataProviderInterface
    {
        return new SuggestionDataProvider($this->getBundleConfig(), $this->createProductFinder());
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface
     */
    public function getCartClient(): QuickOrderPageToCartClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientInterface
     */
    public function getProductStorageClient(): QuickOrderPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToSearchClientInterface
     */
    public function getSearchClient(): QuickOrderPageToSearchClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_SEARCH);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToGlossaryStorageClientInterface
     */
    public function getGlossaryClient(): QuickOrderPageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_GLOSSARY);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToStoreClientInterface
     */
    public function getStoreClient(): QuickOrderPageToStoreClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToLocaleClientInterface
     */
    public function getLocaleClient(): QuickOrderPageToLocaleClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToMessengerClientInterface
     */
    public function getMessengerClient(): QuickOrderPageToMessengerClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_MESSENGER);
    }
}
