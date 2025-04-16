<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types = 1);

namespace SprykerShop\Yves\PaymentAppWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToLocaleClientBridge;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToPaymentAppClientBridge;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToPaymentClientBridge;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToQuoteClientBridge;
use SprykerShop\Yves\PaymentAppWidget\Dependency\Client\PaymentAppWidgetToSalesClientBridge;

/**
 * @method \SprykerShop\Yves\PaymentAppWidget\PaymentAppWidgetConfig getConfig()
 */
class PaymentAppWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const CLIENT_PAYMENT = 'CLIENT_PAYMENT';

    /**
     * @var string
     */
    public const CLIENT_PAYMENT_APP = 'CLIENT_PAYMENT_APP';

    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @var string
     */
    public const CLIENT_SALES = 'CLIENT_SALES';

    /**
     * @uses \Spryker\Yves\Form\Plugin\Application\FormApplicationPlugin::SERVICE_FORM_CSRF_PROVIDER
     *
     * @var string
     */
    public const SERVICE_FORM_CSRF_PROVIDER = 'form.csrf_provider';

    /**
     * @uses \Spryker\Yves\Router\Plugin\Application\RouterApplicationPlugin::SERVICE_ROUTER
     *
     * @var string
     */
    public const SERVICE_ROUTER = 'routers';

    /**
     * @var string
     */
    public const PLUGIN_EXPRESS_CHECKOUT_PAYMENT_WIDGET_RENDER_STRATEGY = 'PLUGIN_EXPRESS_CHECKOUT_PAYMENT_WIDGET_RENDER_STRATEGY';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_REQUEST_STACK
     *
     * @var string
     */
    public const SERVICE_REQUEST_STACK = 'request_stack';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addSalesClient($container);
        $container = $this->addPaymentClient($container);
        $container = $this->addPaymentAppClient($container);
        $container = $this->addLocaleClient($container);
        $container = $this->addRouter($container);
        $container = $this->addFormCsrfProviderService($container);
        $container = $this->addRequestStack($container);
        $container = $this->addExpressCheckoutPaymentWidgetRenderStrategyPlugins($container);

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
    protected function addFormCsrfProviderService(Container $container): Container
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
    protected function addRequestStack(Container $container): Container
    {
        $container->set(static::SERVICE_REQUEST_STACK, function (Container $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addLocaleClient(Container $container): Container
    {
        $container->set(static::CLIENT_LOCALE, function (Container $container) {
            return new PaymentAppWidgetToLocaleClientBridge(
                $container->getLocator()->locale()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPaymentClient(Container $container): Container
    {
        $container->set(static::CLIENT_PAYMENT, function (Container $container) {
            return new PaymentAppWidgetToPaymentClientBridge($container->getLocator()->payment()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPaymentAppClient(Container $container): Container
    {
        $container->set(static::CLIENT_PAYMENT_APP, function (Container $container) {
            return new PaymentAppWidgetToPaymentAppClientBridge($container->getLocator()->paymentApp()->client());
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
            return new PaymentAppWidgetToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSalesClient(Container $container): Container
    {
        $container->set(static::CLIENT_SALES, function (Container $container) {
            return new PaymentAppWidgetToSalesClientBridge($container->getLocator()->sales()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addExpressCheckoutPaymentWidgetRenderStrategyPlugins(Container $container): Container
    {
        $container->set(static::PLUGIN_EXPRESS_CHECKOUT_PAYMENT_WIDGET_RENDER_STRATEGY, function () {
            return $this->getExpressCheckoutPaymentWidgetRenderStrategyPlugins();
        });

        return $container;
    }

    /**
     * @return list<\SprykerShop\Yves\PaymentAppWidgetExtension\Dependency\Plugin\ExpressCheckoutPaymentWidgetRenderStrategyPluginInterface>
     */
    protected function getExpressCheckoutPaymentWidgetRenderStrategyPlugins(): array
    {
        return [];
    }
}
