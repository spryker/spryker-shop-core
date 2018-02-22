<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuickOrderPage;


use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\QuickOrderPage\Form\FormFactory;
use SprykerShop\Yves\QuickOrderPage\Handler\QuickOrderFormOperationHandler;
use SprykerShop\Yves\QuickOrderPage\Handler\QuickOrderFormOperationHandlerInterface;
use SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface;


class QuickOrderPageFactory extends AbstractFactory
{
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
        return new QuickOrderFormOperationHandler($this->getCartClient());
    }

    /**
     * @return \Spryker\Shared\Kernel\Communication\Application
     */
    public function getApplication()
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    public function getMessenger()
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::FLASH_MESSENGER);
    }

    /**
     * @return \SprykerShop\Yves\QuickOrderPage\Dependency\Client\QuickOrderPageToCartClientInterface
     */
    public function getCartClient(): QuickOrderPageToCartClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \Spryker\Client\Catalog\CatalogClientInterface
     */
    public function getCatalogClient(): \Spryker\Client\Catalog\CatalogClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_CATALOG);
    }

    /**
     * @return \Spryker\Client\ProductStorage\ProductStorageClientInterface
     */
    public function getProductStorageClient(): \Spryker\Client\ProductStorage\ProductStorageClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface
     */
    public function getPriceProductStorageClient(): \Spryker\Client\PriceProductStorage\PriceProductStorageClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_PRICE_PRODUCT_STORAGE);
    }

    /**
     * @return \Spryker\Client\PriceProduct\PriceProductClientInterface
     */
    public function getPriceProductClient(): \Spryker\Client\PriceProduct\PriceProductClientInterface
    {
        return $this->getProvidedDependency(QuickOrderPageDependencyProvider::CLIENT_PRICE_PRODUCT);
    }

}
