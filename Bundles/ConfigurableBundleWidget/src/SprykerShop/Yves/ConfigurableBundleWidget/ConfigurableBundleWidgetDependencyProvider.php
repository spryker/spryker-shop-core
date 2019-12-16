<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ConfigurableBundleWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToConfigurableBundleCartClientBridge;
use SprykerShop\Yves\ConfigurableBundleWidget\Dependency\Client\ConfigurableBundleWidgetToQuoteClientBridge;

class ConfigurableBundleWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';
    public const CLIENT_CONFIGURABLE_BUNDLE_CART = 'CLIENT_CONFIGURABLE_BUNDLE_CART';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addQuoteClient($container);
        $container = $this->addConfigurableBundleCartClient($container);

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

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addConfigurableBundleCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONFIGURABLE_BUNDLE_CART, function (Container $container) {
            return new ConfigurableBundleWidgetToConfigurableBundleCartClientBridge($container->getLocator()->configurableBundleCart()->client());
        });

        return $container;
    }
}
