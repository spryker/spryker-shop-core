<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerReorderWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToCartClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToProductStorageClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Dependency\Client\CustomerReorderWidgetToSalesClientInterface;
use SprykerShop\Yves\CustomerReorderWidget\Handler\ReorderHandler;
use SprykerShop\Yves\CustomerReorderWidget\Plugin\Provider\AttributeVariantsProvider;
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
            $this->getSalesClient()
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

//
//    /**
//     * @return \Spryker\Yves\Kernel\Application
//     */
//    protected function getApplication()
//    {
//        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::PLUGIN_APPLICATION);
//    }
//
//    /**
//     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
//     */
//    protected function getFlashMessenger()
//    {
//        return $this->getApplication()['flash_messenger'];
//    }
//
//    /**
//     * @return string
//     */
//    protected function getLocale()
//    {
//        return $this->getApplication()['locale'];
//    }
//
//    /**
//     * @return \Symfony\Component\HttpFoundation\Request
//     */
//    protected function getRequest()
//    {
//        return $this->getApplication()['request'];
//    }
//
//    /**
//     * @return \SprykerShop\Yves\CartPage\Dependency\Plugin\CartVariantAttributeMapperPluginInterface
//     */
//    public function getCartVariantAttributeMapperPlugin()
//    {
//        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::PLUGIN_CART_VARIANT);
//    }
//
//    /**
//     * @return mixed
//     */
//    public function getCartPageWidgetPlugins()
//    {
//        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::PLUGIN_CART_PAGE_WIDGETS);
//    }
//
//    /**
//     * @return \SprykerShop\Yves\CartPage\Plugin\Provider\AttributeVariantsProvider
//     */
//    public function createCartItemsAttributeProvider()
//    {
//        return new AttributeVariantsProvider(
//            $this->getCartVariantAttributeMapperPlugin(),
//            $this->createCartItemHandler()
//        );
//    }
//
//    /**
//     * @return \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface
//     */
//    protected function getProductStorageClient(): CartPageToProductStorageClientInterface
//    {
//        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
//    }
//
//    /**
//     * @return \SprykerShop\Yves\CartPage\Model\CartItemReaderInterface
//     */
//    public function createCartItemReader()
//    {
//        return new CartItemReader($this->getCartItemTransformerPlugins());
//    }
//
//    /**
//     * @return \SprykerShop\Yves\CartPage\Dependency\Plugin\CartItemTransformerPluginInterface[]
//     */
//    protected function getCartItemTransformerPlugins()
//    {
//        return $this->getProvidedDependency(CustomerReorderWidgetDependencyProvider::PLUGIN_CART_ITEM_TRANSFORMERS);
//    }
//
//    /**
//     * @return \SprykerShop\Yves\CartPage\Mapper\CartItemsAttributeMapper
//     */
//    public function createCartItemsAttributeMapper()
//    {
//        return new CartItemsAttributeMapper(
//            $this->getProductStorageClient(),
//            $this->createCartItemsAvailabilityMapper()
//        );
//    }
//
//    /**
//     * @return \SprykerShop\Yves\CartPage\Mapper\CartItemsAvailabilityMapper
//     */
//    public function createCartItemsAvailabilityMapper()
//    {
//        return new CartItemsAvailabilityMapper($this->getAvailabilityStorageClient());
//    }
}
