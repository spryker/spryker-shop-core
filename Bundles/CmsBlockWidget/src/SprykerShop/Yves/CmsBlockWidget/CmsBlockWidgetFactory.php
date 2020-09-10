<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CmsBlockWidget;

use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface;
use SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface;
use SprykerShop\Yves\CmsBlockWidget\Twig\CmsBlockPlaceholderTwigFunctionProvider;
use SprykerShop\Yves\CmsBlockWidget\Twig\CmsBlockTwigFunctionProvider;
use SprykerShop\Yves\CmsBlockWidget\Validator\CmsBlockValidator;
use SprykerShop\Yves\CmsBlockWidget\Validator\CmsBlockValidatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\TwigFunction;

class CmsBlockWidgetFactory extends AbstractFactory
{
    /**
     * @param string $localeName
     *
     * @return \Spryker\Shared\Twig\TwigFunctionProvider
     */
    public function createCmsBlockTwigFunctionProvider(string $localeName): TwigFunctionProvider
    {
        return new CmsBlockTwigFunctionProvider(
            $this->getCmsBlockStorageClient(),
            $this->getStoreClient(),
            $this->createCmsBlockValidator(),
            $localeName
        );
    }

    /**
     * @param string $localeName
     *
     * @return \Twig\TwigFunction
     */
    public function createCmsBlockTwigFunction(string $localeName): TwigFunction
    {
        $functionProvider = $this->createCmsBlockTwigFunctionProvider($localeName);

        return new TwigFunction(
            $functionProvider->getFunctionName(),
            $functionProvider->getFunction(),
            $functionProvider->getOptions()
        );
    }

    /**
     * @return \Spryker\Shared\Twig\TwigFunctionProvider
     */
    public function createCmsBlockPlaceholderTwigFunctionProvider(): TwigFunctionProvider
    {
        return new CmsBlockPlaceholderTwigFunctionProvider($this->getTranslatorService(), $this->getCmsTwigContentRendererPlugin());
    }

    /**
     * @return \Twig\TwigFunction
     */
    public function createCmsBlockPlaceholderTwigFunction(): TwigFunction
    {
        $functionProvider = $this->createCmsBlockPlaceholderTwigFunctionProvider();

        return new TwigFunction(
            $functionProvider->getFunctionName(),
            $functionProvider->getFunction(),
            $functionProvider->getOptions()
        );
    }

    /**
     * @return \SprykerShop\Yves\CmsBlockWidget\Validator\CmsBlockValidatorInterface
     */
    public function createCmsBlockValidator(): CmsBlockValidatorInterface
    {
        return new CmsBlockValidator();
    }

    /**
     * @return \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToStoreClientInterface
     */
    public function getStoreClient(): CmsBlockWidgetToStoreClientInterface
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::CLIENT_STORE);
    }

    /**
     * @return \Spryker\Shared\Twig\TwigExtensionInterface[]
     */
    public function getTwigExtensionPlugins(): array
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::TWIG_EXTENSION_PLUGINS);
    }

    /**
     * @return \SprykerShop\Yves\CmsBlockWidget\Dependency\Client\CmsBlockWidgetToCmsBlockStorageClientInterface
     */
    public function getCmsBlockStorageClient(): CmsBlockWidgetToCmsBlockStorageClientInterface
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::CLIENT_CMS_BLOCK_STORAGE);
    }

    /**
     * @return \Spryker\Yves\CmsContentWidget\Plugin\CmsTwigContentRendererPluginInterface
     */
    public function getCmsTwigContentRendererPlugin(): CmsTwigContentRendererPluginInterface
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::CMS_TWIG_CONTENT_RENDERER_PLUGIN);
    }

    /**
     * @return \Symfony\Contracts\Translation\TranslatorInterface
     */
    public function getTranslatorService(): TranslatorInterface
    {
        return $this->getProvidedDependency(CmsBlockWidgetDependencyProvider::SERVICE_TRANSLATOR);
    }
}
