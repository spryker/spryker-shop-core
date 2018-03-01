<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCustomerClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToMessengerClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Model\AvailabilityChecker;
use SprykerShop\Yves\CustomerReorderWidget\Model\AvailabilityCheckerInterface;
use SprykerShop\Yves\CustomerReorderWidget\Model\CartFiller;
use SprykerShop\Yves\CustomerReorderWidget\Model\CartFillerInterface;
use SprykerShop\Yves\CustomerReorderWidget\Model\ItemFetcher;
use SprykerShop\Yves\CustomerReorderWidget\Model\ItemFetcherInterface;
use SprykerShop\Yves\CustomerReorderWidget\Model\OrderReader;
use SprykerShop\Yves\CustomerReorderWidget\Model\OrderReaderInterface;
use SprykerShop\Yves\CustomerReorderWidget\Model\QuoteWriter;
use SprykerShop\Yves\CustomerReorderWidget\Model\QuoteWriterInterface;

class CustomerReorderWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Model\CartFillerInterface
     */
    public function createCartFiller(): CartFillerInterface
    {
        return new CartFiller(
            $this->getCartClient(),
            $this->createItemsFetcher(),
            $this->createQuoteWriter()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Model\OrderReaderInterface
     */
    public function createOrderReader(): OrderReaderInterface
    {
        return new OrderReader(
            $this->getSalesClient(),
            $this->getCustomerClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Model\AvailabilityCheckerInterface
     */
    public function createAvailabilityChecker(): AvailabilityCheckerInterface
    {
        return new AvailabilityChecker(
            $this->getAvailabilityStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToMessengerClientInterface
     */
    public function getMessengerClient(): CustomerReorderWidgetToMessengerClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_MESSENGER);
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Model\QuoteWriterInterface
     */
    protected function createQuoteWriter(): QuoteWriterInterface
    {
        return new QuoteWriter(
            $this->getCartClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Model\ItemFetcherInterface
     */
    protected function createItemsFetcher(): ItemFetcherInterface
    {
        return new ItemFetcher(
            $this->getProductBundleClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface
     */
    protected function getAvailabilityStorageClient(): CustomerReorderWidgetToAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_AVAILABILITY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCustomerClientInterface
     */
    protected function getCustomerClient(): CustomerReorderWidgetToCustomerClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_CUSTOMER);
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface
     */
    protected function getCartClient(): CustomerReorderWidgetToCartClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface
     */
    protected function getSalesClient(): CustomerReorderWidgetToSalesClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_SALES);
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface
     */
    protected function getProductBundleClient(): CustomerReorderWidgetToProductBundleClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_PRODUCT_BUNDLE);
    }
}
