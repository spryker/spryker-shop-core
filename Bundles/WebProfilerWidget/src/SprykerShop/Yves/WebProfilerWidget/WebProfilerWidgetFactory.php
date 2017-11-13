<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\WebProfilerWidget;

use Spryker\Yves\Kernel\AbstractFactory;

class WebProfilerWidgetFactory extends AbstractFactory
{
    /**
     * @return \Silex\ServiceProviderInterface[]|\Silex\ControllerProviderInterface[]
     */
    public function getWebProfiler()
    {
        return $this->getProvidedDependency(WebProfilerWidgetDependencyProvider::PLUGINS_WEB_PROFILER);
    }
}
