<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget;

use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientInterface;
use SprykerShop\Yves\ContentBannerWidget\Twig\ContentBannerTwigFunction;
use Twig\Environment;

class ContentBannerWidgetFactory extends AbstractFactory
{
    /**
     * @param \Twig\Environment $twig
     * @param string $localeName
     *
     * @return \SprykerShop\Yves\ContentBannerWidget\Twig\ContentBannerTwigFunction
     */
    public function createContentBannerTwigFunction(Environment $twig, string $localeName): ContentBannerTwigFunction
    {
        return new ContentBannerTwigFunction(
            $twig,
            $localeName,
            $this->getContentBannerClient()
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
