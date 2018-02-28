<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;

use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToProductStorageClientInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToSearchClientInterface;
use SprykerShop\Yves\QuickOrderPage\Form\FormFactory;
use SprykerShop\Yves\QuickOrderPage\Form\Validator\TextOrderValidator;
use SprykerShop\Yves\QuickOrderPage\Handler\QuickOrderFormOperationHandler;
use SprykerShop\Yves\QuickOrderPage\Handler\QuickOrderFormOperationHandlerInterface;
use SprykerShop\Yves\QuickOrderPage\Model\ProductFinder;
use SprykerShop\Yves\QuickOrderPage\Model\ProductFinderInterface;
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
     * @return \SprykerShop\Yves\QuickOrderPage\Handler\QuickOrderFormOperationHandlerInterface
     */
    public function createFormOperationHandler(): QuickOrderFormOperationHandlerInterface
    {
        return new QuickOrderFormOperationHandler($this->getConfig(), $this->getCartClient());
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Form\Validator\TextOrderValidator
     */
    public function createTextOrderValidator(): TextOrderValidator
    {
        return new TextOrderValidator($this->getConfig());
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
     * @return \SprykerShop\Yves\QuickOrderPage\Model\ProductFinderInterface
     */
    public function createProductFinder(): ProductFinderInterface
    {
        return new ProductFinder(
            $this->getStore(),
            $this->getSearchClient(),
            $this->getProductStorageClient(),
            $this->getLocale(),
            $this->getBundleConfig()
        );
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
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToGlossaryClientInterface
     */
    public function getGlossaryClient(): QuickOrderPageToGlossaryClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_GLOSSARY);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore(): Store
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::STORE);
    }

    /**
     * @return \Silex\Application
     */
    public function getApplication()
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->getApplication()['locale'];
    }
}
