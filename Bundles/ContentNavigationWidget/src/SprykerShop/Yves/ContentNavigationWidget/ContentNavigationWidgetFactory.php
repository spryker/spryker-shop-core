<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentNavigationWidget;

use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientInterface;
use SprykerShop\Yves\ContentNavigationWidget\Twig\ContentNavigationTwigFunctionProvider;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\ContentNavigationWidget\ContentNavigationWidgetConfig getConfig()
 */
class ContentNavigationWidgetFactory extends AbstractFactory
{
    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \Spryker\Shared\Twig\TwigFunctionProvider
     */
    public function createContentNavigationTwigFunctionProvider(Environment $twig, string $localeName): TwigFunctionProvider
    {
        return new ContentNavigationTwigFunctionProvider(
            $twig,
            $localeName,
            $this->getContentNavigationClient(),
            $this->getNavigationStorageClient(),
            $this->getConfig()
        );
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \Twig\TwigFunction
     */
    public function createContentNavigationTwigFunction(Environment $twig, string $localeName): TwigFunction
    {
        $functionProvider = $this->createContentNavigationTwigFunctionProvider($twig, $localeName);

        return new TwigFunction(
            $functionProvider->getFunctionName(),
            $functionProvider->getFunction(),
            $functionProvider->getOptions()
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
