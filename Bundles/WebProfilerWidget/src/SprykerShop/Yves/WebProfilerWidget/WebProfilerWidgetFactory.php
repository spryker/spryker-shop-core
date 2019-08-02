<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\WebProfilerWidget;

use Spryker\Shared\Twig\Loader\FilesystemLoader;
use Spryker\Shared\Twig\Loader\FilesystemLoaderInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use Symfony\Component\Form\Extension\DataCollector\FormDataCollector;
use Symfony\Component\Form\Extension\DataCollector\FormDataCollectorInterface;
use Symfony\Component\Form\Extension\DataCollector\FormDataExtractor;
use Symfony\Component\Form\Extension\DataCollector\FormDataExtractorInterface;
use Symfony\Component\Form\Extension\DataCollector\Proxy\ResolvedTypeFactoryDataCollectorProxy;
use Symfony\Component\Form\Extension\DataCollector\Type\DataCollectorTypeExtension;
use Symfony\Component\Form\FormTypeExtensionInterface;
use Symfony\Component\Form\ResolvedFormTypeFactory;
use Symfony\Component\Form\ResolvedFormTypeFactoryInterface;

/**
 * @method \SprykerShop\Yves\WebProfilerWidget\WebProfilerWidgetConfig getConfig()
 */
class WebProfilerWidgetFactory extends AbstractFactory
{
    /**
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
     * @return \Symfony\Component\Form\ResolvedFormTypeFactoryInterface
     */
    public function createResolvedTypeFactoryDataCollectorProxy(): ResolvedFormTypeFactoryInterface
    {
        return new ResolvedTypeFactoryDataCollectorProxy(
            $this->createResolvedFormTypeFactory(),
            $this->createFormDataCollector()
        );
    }

    /**
     * @return \Symfony\Component\Form\ResolvedFormTypeFactoryInterface
     */
    public function createResolvedFormTypeFactory(): ResolvedFormTypeFactoryInterface
    {
        return new ResolvedFormTypeFactory();
    }

    /**
     * @return \Symfony\Component\Form\Extension\DataCollector\FormDataCollectorInterface
     */
    public function createFormDataCollector(): FormDataCollectorInterface
    {
        return new FormDataCollector($this->createFormDataExtractor());
    }

    /**
     * @return \Symfony\Component\Form\Extension\DataCollector\FormDataExtractorInterface
     */
    public function createFormDataExtractor(): FormDataExtractorInterface
    {
        return new FormDataExtractor();
    }

    /**
     * @return \Symfony\Component\Form\FormTypeExtensionInterface
     */
    public function createDataCollectorTypeExtension(): FormTypeExtensionInterface
    {
        return new DataCollectorTypeExtension($this->createFormDataCollector());
    }
}
