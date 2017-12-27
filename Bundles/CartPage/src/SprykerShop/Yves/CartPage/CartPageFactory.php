<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CartPage;

use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityStorageClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface;
use SprykerShop\Yves\CartPage\Handler\CartItemHandler;
use SprykerShop\Yves\CartPage\Handler\CartOperationHandler;
use SprykerShop\Yves\CartPage\Handler\ProductBundleCartOperationHandler;
use SprykerShop\Yves\CartPage\Mapper\CartItemsAttributeMapper;
use SprykerShop\Yves\CartPage\Mapper\CartItemsAvailabilityMapper;
use SprykerShop\Yves\CartPage\Model\CartItemReader;
use SprykerShop\Yves\CartPage\Plugin\Provider\AttributeVariantsProvider;

class CartPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Handler\CartOperationHandler
     */
    public function createCartOperationHandler()
    {
        return new CartOperationHandler($this->getCartClient(), $this->getLocale(), $this->getFlashMessenger(), $this->getRequest());
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Handler\ProductBundleCartOperationHandler
     */
    public function createProductBundleCartOperationHandler()
    {
        return new ProductBundleCartOperationHandler(
            $this->createCartOperationHandler(),
            $this->getCartClient(),
            $this->getLocale(),
            $this->getFlashMessenger()
        );
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Handler\CartItemHandlerInterface
     */
    public function createCartItemHandler()
    {
        return new CartItemHandler(
            $this->createCartOperationHandler(),
            $this->getCartClient(),
            $this->getProductStorageClient(),
            $this->getFlashMessenger()
        );
    }

    /**
     * @return \Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper
     */
    public function createProductBundleGrouper()
    {
        return new ProductBundleGrouper();
    }

    /**
     * @return \Spryker\Yves\Kernel\Application
     */
    protected function getApplication()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    protected function getFlashMessenger()
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return string
     */
    protected function getLocale()
    {
        return $this->getApplication()['locale'];
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    protected function getRequest()
    {
        return $this->getApplication()['request'];
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Plugin\CartVariantAttributeMapperPluginInterface
     */
    public function getCartVariantAttributeMapperPlugin()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_CART_VARIANT);
    }

    /**
     * @return mixed
     */
    public function getCartPageWidgetPlugins()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_CART_PAGE_WIDGETS);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Plugin\Provider\AttributeVariantsProvider
     */
    public function createCartItemsAttributeProvider()
    {
        return new AttributeVariantsProvider(
            $this->getCartVariantAttributeMapperPlugin(),
            $this->createCartItemHandler()
        );
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface
     */
    protected function getProductStorageClient(): CartPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityStorageClientInterface
     */
    protected function getAvailabilityStorageClient(): CartPageToAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::CLIENT_AVAILABILITY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Model\CartItemReaderInterface
     */
    public function createCartItemReader()
    {
        return new CartItemReader($this->getCartItemTransformerPlugins());
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Plugin\CartItemTransformerPluginInterface[]
     */
    protected function getCartItemTransformerPlugins()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_CART_ITEM_TRANSFORMERS);
    }

    /**
     * @return CartItemsAttributeMapper
     */
    public function createCartItemsAttributeMapper()
    {
        return new CartItemsAttributeMapper(
            $this->getProductStorageClient(),
            $this->createCartItemsAvailabilityMapper()
        );
    }

    /**
     * @return CartItemsAvailabilityMapper
     */
    public function createCartItemsAvailabilityMapper()
    {
        return new CartItemsAvailabilityMapper($this->getAvailabilityStorageClient());
    }
}
