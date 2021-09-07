<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget;

use Spryker\Shared\Kernel\ContainerInterface;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSearchClientBridge;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToMerchantSwitcherClientBridge;
use SprykerShop\Yves\MerchantSwitcherWidget\Dependency\Client\MerchantSwitcherWidgetToQuoteClientBridge;

class MerchantSwitcherWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @see \Spryker\Shared\Application\ApplicationConstants::FORM_FACTORY
     * @var string
     */
    public const FORM_FACTORY = 'FORM_FACTORY';
    /**
     * @var string
     */
    public const CLIENT_MERCHANT_SEARCH = 'CLIENT_MERCHANT_SEARCH';

    /**
     * @deprecated Will be removed without replacement.
     * @var string
     */
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @var string
     */
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    /**
     * @var string
     */
    public const CLIENT_MERCHANT_SWITCHER = 'CLIENT_MERCHANT_SWITCHER';

    /**
     * @uses \Spryker\Yves\Http\Plugin\Application\HttpApplicationPlugin::SERVICE_COOKIES
     * @var string
     */
    public const SERVICE_COOKIES = 'cookies';

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
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addMerchantSearchClient($container);
        $container = $this->addRequestStack($container);
        $container = $this->addCookies($container);
        $container = $this->addApplication($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addMerchantSwitcherClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMerchantSearchClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_SEARCH, function (Container $container) {
            return new MerchantSwitcherWidgetToMerchantSearchClientBridge($container->getLocator()->merchantSearch()->client());
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
        $container->set(static::SERVICE_REQUEST_STACK, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_REQUEST_STACK);
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCookies(Container $container): Container
    {
        $container->set(static::SERVICE_COOKIES, function (ContainerInterface $container) {
            return $container->getApplicationService(static::SERVICE_COOKIES);
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
    protected function addQuoteClient(Container $container): Container
    {
        $container->set(static::CLIENT_QUOTE, function (Container $container) {
            return new MerchantSwitcherWidgetToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMerchantSwitcherClient(Container $container): Container
    {
        $container->set(static::CLIENT_MERCHANT_SWITCHER, function (Container $container) {
            return new MerchantSwitcherWidgetToMerchantSwitcherClientBridge($container->getLocator()->merchantSwitcher()->client());
        });

        return $container;
    }
}
