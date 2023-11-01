<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointCartPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToLocaleClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToMessengerClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToQuoteClientInterface;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToServicePointCartClientInterface;
use SprykerShop\Yves\ServicePointCartPage\MessageAdder\MessageAdder;
use SprykerShop\Yves\ServicePointCartPage\MessageAdder\MessageAdderInterface;
use SprykerShop\Yves\ServicePointCartPage\Replacer\QuoteItemReplacer;
use SprykerShop\Yves\ServicePointCartPage\Replacer\QuoteItemReplacerInterface;

/**
 * @method \SprykerShop\Yves\ServicePointCartPage\ServicePointCartPageConfig getConfig()
 */
class ServicePointCartPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\ServicePointCartPage\Replacer\QuoteItemReplacerInterface
     */
    public function createQuoteItemReplacer(): QuoteItemReplacerInterface
    {
        return new QuoteItemReplacer(
            $this->createMessageAdder(),
            $this->getServicePointCartClient(),
            $this->getQuoteClient(),
            $this->getConfig(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ServicePointCartPage\MessageAdder\MessageAdderInterface
     */
    public function createMessageAdder(): MessageAdderInterface
    {
        return new MessageAdder(
            $this->getGlossaryStorageClient(),
            $this->getMessengerClient(),
            $this->getLocaleClient(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToServicePointCartClientInterface
     */
    public function getServicePointCartClient(): ServicePointCartPageToServicePointCartClientInterface
    {
        return $this->getProvidedDependency(ServicePointCartPageDependencyProvider::CLIENT_SERVICE_POINT_CART);
    }

    /**
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToQuoteClientInterface
     */
    public function getQuoteClient(): ServicePointCartPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(ServicePointCartPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToGlossaryStorageClientInterface
     */
    public function getGlossaryStorageClient(): ServicePointCartPageToGlossaryStorageClientInterface
    {
        return $this->getProvidedDependency(ServicePointCartPageDependencyProvider::CLIENT_GLOSSARY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToLocaleClientInterface
     */
    public function getLocaleClient(): ServicePointCartPageToLocaleClientInterface
    {
        return $this->getProvidedDependency(ServicePointCartPageDependencyProvider::CLIENT_LOCALE);
    }

    /**
     * @return \SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToMessengerClientInterface
     */
    public function getMessengerClient(): ServicePointCartPageToMessengerClientInterface
    {
        return $this->getProvidedDependency(ServicePointCartPageDependencyProvider::CLIENT_MESSENGER);
    }
}
