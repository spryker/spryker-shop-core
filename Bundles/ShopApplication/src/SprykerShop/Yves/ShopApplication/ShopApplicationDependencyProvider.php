<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\ShopApplication;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;

class ShopApplicationDependencyProvider extends AbstractBundleDependencyProvider
{
    const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    const PLUGIN_BASE_WIDGETS = 'PLUGIN_BASE_WIDGETS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addApplicationPlugin($container);
        $container = $this->addBaseWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplicationPlugin(Container $container)
    {
        $container[self::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();

            return $pimplePlugin->getApplication();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addBaseWidgetPlugins(Container $container)
    {
        $container[self::PLUGIN_BASE_WIDGETS] = function () use ($container) {
            return $this->getBaseWidgetPlugins($container);
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return string[]
     */
    protected function getBaseWidgetPlugins(Container $container): array
    {
        return [];
    }
}
