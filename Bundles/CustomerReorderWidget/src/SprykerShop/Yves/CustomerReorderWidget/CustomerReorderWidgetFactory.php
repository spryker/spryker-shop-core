<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductBundleClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Handler\AvailabilityHandler;
use SprykerShop\Yves\CustomerReorderWidget\Handler\ReorderHandler;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCustomerClientInterface;

class CustomerReorderWidgetFactory extends AbstractFactory
{

    /**
     * @return ReorderHandler
     */
    public function createReorderHandler(): ReorderHandler
    {
        return new ReorderHandler(
            $this->getCartClient(),
            $this->getSalesClient(),
            $this->getProductBundleClient()
        );
    }

    public function createAvailabilityHandler(): AvailabilityHandler
    {
        return new AvailabilityHandler(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return CustomerReorderWidgetToCustomerClientInterface
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
     * @return CustomerReorderWidgetToSalesClientInterface
     */
    protected function getSalesClient(): CustomerReorderWidgetToSalesClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_SALES);
    }

    /**
     * @return CustomerReorderWidgetToProductBundleClientInterface
     */
    protected function getProductBundleClient(): CustomerReorderWidgetToProductBundleClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_PRODUCT_BUNDLE);
    }

    /**
     * @return CustomerReorderWidgetToProductStorageClientInterface
     */
    protected function getProductStorageClient(): CustomerReorderWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
