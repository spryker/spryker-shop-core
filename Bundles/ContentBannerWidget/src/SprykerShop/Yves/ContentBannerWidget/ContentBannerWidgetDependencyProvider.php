<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentBannerWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ContentBannerWidget\Dependency\Client\ContentBannerWidgetToContentBannerClientBridge;

class ContentBannerWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CONTENT_BANNER = 'CLIENT_CONTENT_BANNER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addContentBannerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addContentBannerClient(Container $container): Container
    {
        $container[static::CLIENT_CONTENT_BANNER] = function (Container $container) {
            return new ContentBannerWidgetToContentBannerClientBridge(
                $container->getLocator()->contentBanner()->client()
            );
        };

        return $container;
    }
}
