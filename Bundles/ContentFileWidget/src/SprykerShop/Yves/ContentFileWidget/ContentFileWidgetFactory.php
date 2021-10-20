<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentFileWidget;

use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface;
use SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface;
use SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface;
use SprykerShop\Yves\ContentFileWidget\Expander\IconNameFileStorageDataExpander;
use SprykerShop\Yves\ContentFileWidget\Reader\ContentFileReader;
use SprykerShop\Yves\ContentFileWidget\Reader\ContentFileReaderInterface;
use SprykerShop\Yves\ContentFileWidget\Twig\ContentFileListTwigFunctionProvider;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ContentFileWidget\ContentFileWidgetConfig getConfig()
 */
class ContentFileWidgetFactory extends AbstractFactory
{
    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \Spryker\Shared\Twig\TwigFunctionProvider
     */
    public function createContentFileListTwigFunctionProvider(Environment $twig, string $localeName): TwigFunctionProvider
    {
        return new ContentFileListTwigFunctionProvider(
            $twig,
            $localeName,
            $this->createContentFileReader(),
            $this->getContentFileClient(),
        );
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \Twig\TwigFunction
     */
    public function createContentFileListTwigFunction(Environment $twig, string $localeName): TwigFunction
    {
        $functionProvider = $this->createContentFileListTwigFunctionProvider($twig, $localeName);

        return new TwigFunction(
            $functionProvider->getFunctionName(),
            $functionProvider->getFunction(),
            $functionProvider->getOptions(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentFileWidget\Reader\ContentFileReaderInterface
     */
    public function createContentFileReader(): ContentFileReaderInterface
    {
        return new ContentFileReader(
            $this->getFileManagerStorageClient(),
            $this->getFileStorageDataExpanders(),
        );
    }

    /**
     * @return array<\SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface>
     */
    public function getFileStorageDataExpanders(): array
    {
        return [
            $this->createIconNameFileStorageDataExpander(),
        ];
    }

    /**
     * @return \SprykerShop\Yves\ContentFileWidget\Expander\FileStorageDataExpanderInterface
     */
    public function createIconNameFileStorageDataExpander(): FileStorageDataExpanderInterface
    {
        return new IconNameFileStorageDataExpander($this->getConfig());
    }

    /**
     * @return \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToContentFileClientInterface
     */
    public function getContentFileClient(): ContentFileWidgetToContentFileClientInterface
    {
        return $this->getProvidedDependency(ContentFileWidgetDependencyProvider::CLIENT_CONTENT_FILE);
    }

    /**
     * @return \SprykerShop\Yves\ContentFileWidget\Dependency\Client\ContentFileWidgetToFileManagerStorageClientInterface
     */
    public function getFileManagerStorageClient(): ContentFileWidgetToFileManagerStorageClientInterface
    {
        return $this->getProvidedDependency(ContentFileWidgetDependencyProvider::CLIENT_FILE_MANAGER_STORAGE);
    }
}
