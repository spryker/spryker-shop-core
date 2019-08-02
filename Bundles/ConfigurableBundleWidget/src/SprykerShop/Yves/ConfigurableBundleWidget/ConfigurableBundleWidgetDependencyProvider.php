<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToCartClientBridge;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToConfigurableBundleClientBridge;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToQuoteClientBridge;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToZedRequestClientBridge;

class ConfigurableBundleWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CONFIGURABLE_BUNDLE = 'CLIENT_CONFIGURABLE_BUNDLE';
    public const CLIENT_CART = 'CLIENT_CART';
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addConfigurableBundleClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addQuoteClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addConfigurableBundleClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONFIGURABLE_BUNDLE, function (Container $container) {
            return new ConfigurableBundleWidgetToConfigurableBundleClientBridge($container->getLocator()->configurableBundle()->client());
        });

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
            return new ConfigurableBundleWidgetToCartClientBridge($container->getLocator()->cart()->client());
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
            return new ConfigurableBundleWidgetToZedRequestClientBridge($container->getLocator()->zedRequest()->client());
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
            return new ConfigurableBundleWidgetToQuoteClientBridge($container->getLocator()->quote()->client());
        });

        return $container;
    }
}
