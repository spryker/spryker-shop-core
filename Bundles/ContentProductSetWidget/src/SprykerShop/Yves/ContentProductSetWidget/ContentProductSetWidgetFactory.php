<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductSetWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToContentProductSetClientInterface;
use SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductSetStorageClientInterface;
use SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductStorageClientInterface;
use SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductAbstractReader;
use SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductAbstractReaderInterface;
use SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductSetReader;
use SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductSetReaderInterface;
use SprykerShop\Yves\ContentProductSetWidget\Twig\ContentProductSetTwigFunction;
use Twig\Environment;

class ContentProductSetWidgetFactory extends AbstractFactory
{
    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \SprykerShop\Yves\ContentProductSetWidget\Twig\ContentProductSetTwigFunction
     */
    public function createContentProductSetTwigFunction(
        Environment $twig,
        string $localeName
    ): ContentProductSetTwigFunction {
        return new ContentProductSetTwigFunction(
            $twig,
            $localeName,
            $this->createContentProductSetReader(),
            $this->createContentProductAbstractReader()
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductSetReaderInterface
     */
    public function createContentProductSetReader(): ContentProductSetReaderInterface
    {
        return new ContentProductSetReader(
            $this->getContentProductSetClient(),
            $this->getProductSetStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentProductSetWidget\Reader\ContentProductAbstractReaderInterface
     */
    public function createContentProductAbstractReader(): ContentProductAbstractReaderInterface
    {
        return new ContentProductAbstractReader(
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToContentProductSetClientInterface
     */
    public function getContentProductSetClient(): ContentProductSetWidgetToContentProductSetClientInterface
    {
        return $this->getProvidedDependency(ContentProductSetWidgetDependencyProvider::CLIENT_CONTENT_PRODUCT_SET);
    }

    /**
     * @return \SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductSetStorageClientInterface
     */
    public function getProductSetStorageClient(): ContentProductSetWidgetToProductSetStorageClientInterface
    {
        return $this->getProvidedDependency(ContentProductSetWidgetDependencyProvider::CLIENT_PRODUCT_SET_STORAGE);
    }

    /**
     * @return \SprykerShop\Yves\ContentProductSetWidget\Dependency\Client\ContentProductSetWidgetToProductStorageClientInterface
     */
    public function getProductStorageClient(): ContentProductSetWidgetToProductStorageClientInterface
    {
        return $this->getProvidedDependency(ContentProductSetWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
