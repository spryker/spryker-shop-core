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
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Handler\AvailabilityHandler;
use SprykerShop\Yves\CustomerReorderWidget\Handler\AvailabilityHandlerInterface;
use SprykerShop\Yves\CustomerReorderWidget\Handler\MessengerHandler;
use SprykerShop\Yves\CustomerReorderWidget\Handler\MessengerHandlerInterface;
use SprykerShop\Yves\CustomerReorderWidget\Handler\OrderHandler;
use SprykerShop\Yves\CustomerReorderWidget\Handler\OrderHandlerInterface;
use SprykerShop\Yves\CustomerReorderWidget\Handler\ReorderHandler;
use SprykerShop\Yves\CustomerReorderWidget\Handler\ReorderHandlerInterface;

class CustomerReorderWidgetFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Handler\ReorderHandlerInterface
     */
    public function createReorderHandler(): ReorderHandlerInterface
    {
        return new ReorderHandler(
            $this->getCartClient(),
            $this->getProductBundleClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Handler\OrderHandlerInterface
     */
    public function createOrderHandler(): OrderHandlerInterface
    {
        return new OrderHandler(
            $this->getSalesClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\CustomerReorderWidget\Handler\MessengerHandlerInterface
     */
    public function createMessengerHandler(): MessengerHandlerInterface
    {
        return new MessengerHandler(
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
     * @return \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCustomerClientInterface
     */
    public function getCustomerClient(): CustomerReorderWidgetToCustomerClientInterface
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
     * @return \SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface
     */
    protected function getProductStorageClient(): CustomerReorderWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
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
