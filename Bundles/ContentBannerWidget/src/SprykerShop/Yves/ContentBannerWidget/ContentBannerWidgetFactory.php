<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget;

use Spryker\Shared\Twig\TwigFunctionProvider;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientInterface;
use SprykerShop\Yves\ContentBannerWidget\Twig\ContentBannerTwigFunctionProvider;
use Twig\Environment;
use Twig\TwigFunction;

class ContentBannerWidgetFactory extends AbstractFactory
{
    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \Spryker\Shared\Twig\TwigFunctionProvider
     */
    public function createContentBannerTwigFunctionProvider(Environment $twig, string $localeName): TwigFunctionProvider
    {
        return new ContentBannerTwigFunctionProvider(
            $twig,
            $localeName,
            $this->getContentBannerClient(),
        );
    }

    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \Twig\TwigFunction
     */
    public function createContentBannerTwigFunction(Environment $twig, string $localeName): TwigFunction
    {
        $functionProvider = $this->createContentBannerTwigFunctionProvider($twig, $localeName);

        return new TwigFunction(
            $functionProvider->getFunctionName(),
            $functionProvider->getFunction(),
            $functionProvider->getOptions(),
        );
    }

    /**
     * @return \SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientInterface
     */
    public function getContentBannerClient(): ContentBannerWidgetToContentBannerClientInterface
    {
        return $this->getProvidedDependency(ContentBannerWidgetDependencyProvider::CLIENT_CONTENT_BANNER);
    }
}
