<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityStorageClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToZedRequestClientInterface;
use SprykerShop\Yves\CartPage\Dependency\Service\CartPageToUtilNumberServiceInterface;
use SprykerShop\Yves\CartPage\Expander\MiniCartViewExpander;
use SprykerShop\Yves\CartPage\Expander\MiniCartViewExpanderInterface;
use SprykerShop\Yves\CartPage\Form\FormFactory;
use SprykerShop\Yves\CartPage\Handler\CartItemHandler;
use SprykerShop\Yves\CartPage\Mapper\CartItemsAttributeMapper;
use SprykerShop\Yves\CartPage\Mapper\CartItemsAvailabilityMapper;
use SprykerShop\Yves\CartPage\Model\CartItemReader;
use SprykerShop\Yves\CartPage\Plugin\Provider\AttributeVariantsProvider;
use SprykerShop\Yves\CartPage\ProductViewExpander\ProductViewExpander;
use SprykerShop\Yves\CartPage\ProductViewExpander\ProductViewExpanderInterface;
use SprykerShop\Yves\CartPage\ViewModel\CartPageView;
use SprykerShop\Yves\CartPage\ViewModel\CartPageViewInterface;
use Symfony\Cmf\Component\Routing\ChainRouterInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Twig\Environment;

/**
 * @method \SprykerShop\Yves\CartPage\CartPageConfig getConfig()
 */
class CartPageFactory extends AbstractFactory
{
    /**
     * @return \SprykerShop\Yves\CartPage\ViewModel\CartPageViewInterface
     */
    public function createCartPageView(): CartPageViewInterface
    {
        return new CartPageView(
            $this->getConfig(),
            $this->getCartClient(),
            $this->getQuoteClient(),
            $this->createCartItemReader(),
            $this->createCartItemsAttributeProvider(),
            $this->createCartPageFormFactory(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientInterface
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientInterface
     */
    public function getQuoteClient(): CartPageToQuoteClientInterface
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::CLIENT_QUOTE);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Handler\CartItemHandlerInterface
     */
    public function createCartItemHandler()
    {
        return new CartItemHandler(
            $this->getCartClient(),
            $this->getProductStorageClient(),
            $this->getZedRequestClient(),
        );
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Yves\Kernel\Application
     */
    public function getApplication()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_APPLICATION);
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @return \Spryker\Yves\Messenger\FlashMessenger\FlashMessengerInterface
     */
    public function getFlashMessenger()
    {
        return $this->getApplication()['flash_messenger'];
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::SERVICE_LOCALE);
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->getRequestStack()->getCurrentRequest();
    }

    /**
     * @return \Symfony\Component\HttpFoundation\RequestStack
     */
    public function getRequestStack(): RequestStack
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::SERVICE_REQUEST_STACK);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Plugin\CartVariantAttributeMapperPluginInterface
     */
    public function getCartVariantAttributeMapperPlugin()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_CART_VARIANT);
    }

    /**
     * @return array<string>
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
            $this->createCartItemHandler(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientInterface
     */
    public function getProductStorageClient(): CartPageToProductStorageClientInterface
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityStorageClientInterface
     */
    public function getAvailabilityStorageClient(): CartPageToAvailabilityStorageClientInterface
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::CLIENT_AVAILABILITY_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Client\CartPageToZedRequestClientInterface
     */
    public function getZedRequestClient(): CartPageToZedRequestClientInterface
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::CLIENT_ZED_REQUEST);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Model\CartItemReaderInterface
     */
    public function createCartItemReader()
    {
        return new CartItemReader($this->getCartItemTransformerPlugins());
    }

    /**
     * @return array<\SprykerShop\Yves\CartPageExtension\Dependency\Plugin\CartItemTransformerPluginInterface>
     */
    public function getCartItemTransformerPlugins()
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_CART_ITEM_TRANSFORMERS);
    }

    /**
     * @return array<\SprykerShop\Yves\CartPageExtension\Dependency\Plugin\PreAddToCartPluginInterface>
     */
    public function getPreAddToCartPlugins(): array
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGIN_PRE_ADD_TO_CART);
    }

    /**
     * @return array<\SprykerShop\Yves\CartPageExtension\Dependency\Plugin\MiniCartViewExpanderPluginInterface>
     */
    public function getMiniCartViewExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGINS_MINI_CART_VIEW_EXPANDER);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Mapper\CartItemsAttributeMapper
     */
    public function createCartItemsAttributeMapper()
    {
        return new CartItemsAttributeMapper(
            $this->getProductStorageClient(),
            $this->createCartItemsAvailabilityMapper(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Mapper\CartItemsAvailabilityMapper
     */
    public function createCartItemsAvailabilityMapper()
    {
        return new CartItemsAvailabilityMapper($this->getAvailabilityStorageClient());
    }

    /**
     * @return \SprykerShop\Yves\CartPage\ProductViewExpander\ProductViewExpanderInterface
     */
    public function createProductViewExpander(): ProductViewExpanderInterface
    {
        return new ProductViewExpander($this->getRouter());
    }

    /**
     * @return \Symfony\Cmf\Component\Routing\ChainRouterInterface
     */
    public function getRouter(): ChainRouterInterface
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::SERVICE_ROUTER);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Form\FormFactory
     */
    public function createCartPageFormFactory(): FormFactory
    {
        return new FormFactory();
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Expander\MiniCartViewExpanderInterface
     */
    public function createMiniCartViewExpander(): MiniCartViewExpanderInterface
    {
        return new MiniCartViewExpander(
            $this->getTwigEnvironment(),
            $this->getConfig(),
        );
    }

    /**
     * @return \Symfony\Component\Security\Csrf\CsrfTokenManagerInterface
     */
    public function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::SERVICE_FORM_CSRF_PROVIDER);
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Service\CartPageToUtilNumberServiceInterface
     */
    public function getUtilNumberService(): CartPageToUtilNumberServiceInterface
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::SERVICE_UTIL_NUMBER);
    }

    /**
     * @return array<\SprykerShop\Yves\CartPageExtension\Dependency\Plugin\AddToCartFormWidgetParameterExpanderPluginInterface>
     */
    public function getAddToCartFormWidgetParameterExpanderPlugins(): array
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::PLUGINS_ADD_TO_CART_FORM_WIDGET_PARAMETER_EXPANDER);
    }

    /**
     * @return \Twig\Environment
     */
    public function getTwigEnvironment(): Environment
    {
        return $this->getProvidedDependency(CartPageDependencyProvider::TWIG_ENVIRONMENT);
    }
}
