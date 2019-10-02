<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WebProfilerWidget;

use Spryker\Shared\Twig\Loader\FilesystemLoader;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\HttpKernel\Profiler\FileProfilerStorage;
use Symfony\Component\HttpKernel\Profiler\Profiler;
use Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface;
use Symfony\Component\Stopwatch\Stopwatch;
use Twig\Profiler\Profile;

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
     * @return \Spryker\Shared\WebProfilerExtension\Dependency\Plugin\WebProfilerDataCollectorPluginInterface[]
     */
    public function getDataCollectorPlugins(): array
    {
        return $this->getProvidedDependency(WebProfilerWidgetDependencyProvider::PLUGINS_DATA_COLLECTORS);
    }

    /**
     * @return \Symfony\Component\Stopwatch\Stopwatch
     */
    public function createStopwatch(): Stopwatch
    {
        return new Stopwatch();
    }

    /**
     * @return \Symfony\Component\HttpKernel\Profiler\Profiler
     */
    public function createProfiler(): Profiler
    {
        return new Profiler($this->createProfilerStorage());
    }

    /**
     * @return \Symfony\Component\HttpKernel\Profiler\ProfilerStorageInterface
     */
    public function createProfilerStorage(): ProfilerStorageInterface
    {
        return new FileProfilerStorage('file:' . $this->getConfig()->getProfilerCacheDirectory());
    }

    /**
     * @return \Twig\Profiler\Profile
     */
    public function createProfile(): Profile
    {
        return new Profile();
    }
}
