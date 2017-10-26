<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CartPage;

use SprykerShop\Yves\CartPage\Handler\CartItemHandler;
use SprykerShop\Yves\CartPage\Handler\CartOperationHandler;
use SprykerShop\Yves\CartPage\Handler\ProductBundleCartOperationHandler;
use SprykerShop\Yves\CartPage\Plugin\Provider\AttributeVariantsProvider;
use Spryker\Yves\Kernel\AbstractFactory;
use Spryker\Yves\ProductBundle\Grouper\ProductBundleGrouper;
use SprykerShop\Yves\ProductDetailPage\Mapper\AttributeVariantMapper;

class CartPageFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Client\Calculation\CalculationClientInterface
     */
    public function getCalculationClient()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::CLIENT_CALCULATION);
    }

    /**
     * @return \Spryker\Client\Cart\CartClient
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
            $this->getProductClient(),
            $this->getStorageProductMapperPlugin(),
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
     * @return \Pyz\Yves\Checkout\Plugin\CheckoutBreadcrumbPlugin
     */
    public function getCheckoutBreadcrumbPlugin()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_CHECKOUT_BREADCRUMB);
    }

    /**
     * @return \Spryker\Yves\CartVariant\Dependency\Plugin\CartVariantAttributeMapperPluginInterface
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
     * @return \SprykerShop\Yves\ProductDetailPage\Mapper\AttributeVariantMapperInterface
     */
    protected function createAttributeVariantMapper()
    {
        return new AttributeVariantMapper($this->getProductClient());
    }

    /**
     * @return \SprykerShop\Yves\ProductDetailPage\Dependency\Plugin\StorageProductMapperPluginInterface
     */
    protected function getStorageProductMapperPlugin()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_STORAGE_PRODUCT_MAPPER);
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
     * @return \Spryker\Client\Product\ProductClientInterface $productClient
     */
    protected function getProductClient()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::CLIENT_PRODUCT);
    }

    /**
     * @return \Spryker\Yves\DiscountPromotion\Dependency\PromotionProductMapperPluginInterface
     */
    public function getProductPromotionMapperPlugin()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_PROMOTION_PRODUCT_MAPPER);
    }
}
