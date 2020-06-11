<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentNavigationWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Twig\ContentNavigationTwigFunction;
use Twig\Environment;

class ContentNavigationWidgetFactory extends AbstractFactory
{
    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \SprykerShop\Yves\ContentNavigationWidget\Twig\ContentNavigationTwigFunction
     */
    public function createContentNavigationTwigFunction(Environment $twig, string $localeName): ContentNavigationTwigFunction
    {
        return new ContentNavigationTwigFunction(
            $twig,
            $localeName,
            $this->getContentNavigationClient(),
            $this->getNavigationStorageClient()
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface
     */
    public function getContentNavigationClient(): ContentNavigationWidgetToContentNavigationClientInterface
    {
        return $this->getProvidedDependency(ContentNavigationWidgetDependencyProvider::CLIENT_CONTENT_NAVIGATION);
    }

    /**
     * @return \SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface
     */
    public function getNavigationStorageClient(): ContentNavigationWidgetToNavigationStorageClientInterface
    {
        return $this->getProvidedDependency(ContentNavigationWidgetDependencyProvider::CLIENT_NAVIGATION_STORAGE);
    }
}
