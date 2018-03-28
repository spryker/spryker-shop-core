<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\MultiCartWidget\Dependency\Client\MultiCartWidgetToMultiCartClientBridge;

class MultiCartWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MULTI_CART = 'CLIENT_MULTI_CART';
    public const PLUGINS_VIEW_EXTEND = 'PLUGINS_VIEW_EXTEND';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return array|\Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addMultiCartClient($container);
        $container = $this->addViewExtendWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMultiCartClient($container)
    {
        $container[static::CLIENT_MULTI_CART] = function (Container $container) {
            return new MultiCartWidgetToMultiCartClientBridge($container->getLocator()->multiCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addViewExtendWidgetPlugins(Container $container)
    {
        $container[static::PLUGINS_VIEW_EXTEND] = function () {
            return $this->getViewExtendWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getViewExtendWidgetPlugins(): array
    {
        return [];
    }
}
