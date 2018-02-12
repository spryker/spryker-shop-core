<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\Kernel\Application;
use Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCustomerClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Handler\CartFiller;
use SprykerShop\Yves\CustomerReorderWidget\Handler\CartFillerInteface;
use SprykerShop\Yves\CustomerReorderWidget\Handler\ItemsFetcher;
use SprykerShop\Yves\CustomerReorderWidget\Handler\ItemsFetcherInterface;
use SprykerShop\Yves\CustomerReorderWidget\Handler\Messenger;
use SprykerShop\Yves\CustomerReorderWidget\Handler\MessengerInterface;
use SprykerShop\Yves\CustomerReorderWidget\Handler\OrderRepository;
use SprykerShop\Yves\CustomerReorderWidget\Handler\OrderRepositoryInterface;

class CustomerReorderWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Handler\CartFillerInteface
     */
    public function createCartFiller(): CartFillerInteface
    {
        return new CartFiller(
            $this->getCartClient(),
            $this->getProductBundleClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Handler\OrderRepositoryInterface
     */
    public function createOrderRepository(): OrderRepositoryInterface
    {
        return new OrderRepository(
            $this->getSalesClient(),
            $this->getCustomerClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Handler\MessengerInterface
     */
    public function createMessenger(): MessengerInterface
    {
        return new Messenger(
            $this->getFlashMessenger(),
            $this->getCartClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToAvailabilityStorageClientInterface
     */
    public function getAvailabilityStorageClient(): CustomerReorderWidgetToAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_AVAILABILITY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Handler\ItemsFetcherInterface
     */
    protected function createItemsFetcher(): ItemsFetcherInterface
    {
        return new ItemsFetcher(
            $this->getProductBundleClient()
        );
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

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected function getFlashMessenger(): FlashMessengerInterface
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getApplication(): Application
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::PLUGIN_APPLICATION);
    }
}
