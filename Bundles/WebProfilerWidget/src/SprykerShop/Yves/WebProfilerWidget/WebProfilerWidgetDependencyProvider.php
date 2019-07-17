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
    public const PLUGINS_WEB_PROFILER = 'PLUGINS_WEB_PROFILER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container[static::PLUGINS_WEB_PROFILER] = function () {
            return $this->getWebProfilerPlugins();
        };

        return $container;
    }

    /**
     * @return array
     */
    protected function getWebProfilerPlugins()
    {
        return [];
    }
}
