<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CartPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToAvailabilityStorageClientBridge;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToCartClientBridge;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToProductStorageClientBridge;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToQuoteClientBridge;
use SprykerShop\Yves\CartPage\Dependency\Client\CartPageToZedRequestClientBridge;
use SprykerShop\Yves\CartPage\Plugin\CartVariantAttributeMapperPlugin;

class CartPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_CART = 'CLIENT_CART';
    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    /**
     * @var string
     */
    public const CLIENT_PRODUCT_STORAGE = 'CLIENT_PRODUCT_STORAGE';
    /**
     * @var string
     */
    public const CLIENT_AVAILABILITY_STORAGE = 'CLIENT_AVAILABILITY_STORAGE';
    /**
     * @var string
     */
    public const PLUGIN_CART_VARIANT = 'PLUGIN_CART_VARIANT';
    /**
     * @var string
     */
    public const PLUGIN_CART_ITEM_TRANSFORMERS = 'PLUGIN_CART_ITEM_TRANSFORMERS';
    /**
     * @var string
     */
    public const PLUGIN_CART_PAGE_WIDGETS = 'PLUGIN_CART_PAGE_WIDGETS';
    /**
     * @var string
     */
    public const PLUGIN_PRE_ADD_TO_CART = 'PLUGIN_PRE_ADD_TO_CART';
    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @uses \Spryker\Yves\Form\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     * @var string
     */
    public const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @deprecated Will be removed without replacement.
     * @var string
     */
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     * @var string
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @uses \Spryker\Yves\Locale\Plugin\Application\LocaleApplicationPlugin::SERVICE_LOCALE
     * @var string
     */
    public const SERVICE_LOCALE = 'locale';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCartClient($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addProductStorageClient($container);
        $container = $this->addAvailabilityStorageClient($container);
        $container = $this->addApplication($container);
        $container = $this->addCartVariantAttributeMapperPlugin($container);
        $container = $this->addCartPageWidgetPlugins($container);
        $container = $this->addCartItemTransformerPlugins($container);
        $container = $this->addPreAddToCartPlugins($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addCsrfProviderService($container);
        $container = $this->addRouter($container);
        $container = $this->addLocale($container);
        $container = $this->addRequestStack($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART, function (Container $container) {
            return new CartPageToCartClientBridge($container->getLocator()->cart()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new CartPageToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addProductStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_PRODUCT_STORAGE, function (Container $container) {
            return new CartPageToProductStorageClientBridge($container->getLocator()->productStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addAvailabilityStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_AVAILABILITY_STORAGE, function (Container $container) {
            return new CartPageToAvailabilityStorageClientBridge($container->getLocator()->availabilityStorage()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return new CartPageToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCsrfProviderService(Container $container): Container
    {
        $container->set(static::SERVICE_FORM_CSRF_PROVIDER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_FORM_CSRF_PROVIDER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRouter(Container $container): Container
    {
        $container->set(static::SERVICE_ROUTER, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_ROUTER);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocale(Container $container): Container
    {
        $container->set(static::SERVICE_LOCALE, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_LOCALE);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @deprecated Will be removed without replacement.
     *
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container->set(static::PLUGIN_APPLICATION, function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartVariantAttributeMapperPlugin(Container $container): Container
    {
        $container->set(static::PLUGIN_CART_VARIANT, function () {
            return new CartVariantAttributeMapperPlugin();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartPageWidgetPlugins(Container $container)
    {
        $container->set(static::PLUGIN_CART_PAGE_WIDGETS, function () {
            return $this->getCartPageWidgetPlugins();
        });

        return $container;
    }

    /**
     * Returns a list of widget plugin class names that implement
     * {@link \Spryker\Yves\Kernel\Dependency\Plugin\WidgetPluginInterface}.
     *
     * @return string[]
     */
    protected function getCartPageWidgetPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartItemTransformerPlugins(Container $container)
    {
        $container->set(static::PLUGIN_CART_ITEM_TRANSFORMERS, function () {
            return $this->getCartItemTransformerPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CartPage\Dependency\Plugin\CartItemTransformerPluginInterface[]
     */
    protected function getCartItemTransformerPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPreAddToCartPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_PRE_ADD_TO_CART, function () {
            return $this->getPreAddToCartPlugins();
        });

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CartPageExtension\Dependency\Plugin\PreAddToCartPluginInterface[]
     */
    protected function getPreAddToCartPlugins(): array
    {
        return [];
    }
}
