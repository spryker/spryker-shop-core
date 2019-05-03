<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WebProfilerWidget;

use Spryker\Shared\Twig\Loader\FilesystemLoader;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Yves\Kernel\AbstractFactory;

/**
 * @method \SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetConfig getConfig()
 */
class WebProfilerWidgetFactory extends AbstractFactory
{
    /**
     * @deprecated Use `\SprykerShop\Yves\WebProfilerWidget\Plugin\Application\WebProfilerApplicationPlugin` instead.
     *
     * @return \Silex\ServiceProviderInterface[]
     */
    public function getWebProfiler()
    {
        return $this->getProvidedDependency(WebProfilerWidgetDependencyProvider::PLUGINS_WEB_PROFILER);
    }

    /**
     * @return \Spryker\Shared\Twig\Loader\FilesystemLoaderInterface
     */
    public function createTwigFilesystemLoader(): FilesystemLoaderInterface
    {
        return new FilesystemLoader($this->getConfig()->getWebProfilerTemplatePaths(), 'WebProfiler');
    }

    /**
     * @return \SprykerShop\Yves\WebProfilerWidgetExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface[]
     */
    public function getDataCollectorPlugins(): array
    {
        return $this->getProvidedDependency(WebProfilerWidgetDependencyProvider::PLUGINS_DATA_COLLECTORS);
    }
}
