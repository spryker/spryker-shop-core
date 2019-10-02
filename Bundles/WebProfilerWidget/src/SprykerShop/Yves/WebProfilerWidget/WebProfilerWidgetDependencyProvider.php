<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WebProfilerWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class WebProfilerWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @deprecated Use `\SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetDependencyProvider::PLUGINS_DATA_COLLECTORS` instead.
     */
    public const PLUGINS_WEB_PROFILER = 'PLUGINS_WEB_PROFILER';

    public const PLUGINS_DATA_COLLECTORS = 'PLUGINS_DATA_COLLECTORS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addDataCollectorPlugins($container);

        $container->set(static::PLUGINS_WEB_PROFILER, function () {
            return $this->getWebProfilerPlugins();
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addDataCollectorPlugins(Container $container): Container
    {
        $container->set(static::PLUGINS_DATA_COLLECTORS, function () {
            return $this->getDataCollectorPlugins();
        });

        return $container;
    }

    /**
     * @return \Spryker\Shared\WebProfilerExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface[]
     */
    public function getDataCollectorPlugins()
    {
        return [];
    }

    /**
     * @deprecated Use `\SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetDependencyProvider::getDataCollectorPlugins` instead.
     *
     * @return array
     */
    protected function getWebProfilerPlugins()
    {
        return [];
    }
}
