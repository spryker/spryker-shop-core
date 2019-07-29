<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentProductWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToContentProductClientBridgeInterface;
use SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToProductStorageClientBridgeInterface;
use SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReader;
use SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReaderInterface;
use SprykerShop\Yves\ContentProductWidget\Twig\ContentProductAbstractListTwigFunction;
use Twig\Environment;

class ContentProductWidgetFactory extends AbstractFactory
{
    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \SprykerShop\Yves\ContentProductWidget\Twig\ContentProductAbstractListTwigFunction
     */
    public function createContentProductAbstractListTwigFunction(Environment $twig, string $localeName): ContentProductAbstractListTwigFunction
    {
        return new ContentProductAbstractListTwigFunction(
            $twig,
            $localeName,
            $this->createContentProductAbstractReader()
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentProductWidget\Reader\ContentProductAbstractReaderInterface
     */
    public function createContentProductAbstractReader(): ContentProductAbstractReaderInterface
    {
        return new ContentProductAbstractReader(
            $this->getContentProductClient(),
            $this->getProductStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToContentProductClientBridgeInterface
     */
    public function getContentProductClient(): ContentProductWidgetToContentProductClientBridgeInterface
    {
        return $this->getProvidedDependency(ContentProductWidgetDependencyProvider::CLIENT_CONTENT_PRODUCT);
    }

    /**
     * @return \SprykerShop\Yves\ContentProductWidget\Dependency\Client\ContentProductWidgetToProductStorageClientBridgeInterface
     */
    public function getProductStorageClient(): ContentProductWidgetToProductStorageClientBridgeInterface
    {
        return $this->getProvidedDependency(ContentProductWidgetDependencyProvider::CLIENT_PRODUCT_STORAGE);
    }
}
