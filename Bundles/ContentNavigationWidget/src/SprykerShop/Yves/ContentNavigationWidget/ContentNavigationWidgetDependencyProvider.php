<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ContentNavigationWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToContentNavigationClientBridge;
use SprykerShop\Yves\ContentNavigationWidget\Dependency\Client\ContentNavigationWidgetToNavigationStorageClientBridge;

class ContentNavigationWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CONTENT_NAVIGATION = 'CLIENT_CONTENT_NAVIGATION';
    public const CLIENT_NAVIGATION_STORAGE = 'CLIENT_NAVIGATION_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addContentNavigationClient($container);
        $container = $this->addNavigationStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addContentNavigationClient(Container $container): Container
    {
        $container->set(static::CLIENT_CONTENT_NAVIGATION, function (Container $container) {
            return new ContentNavigationWidgetToContentNavigationClientBridge(
                $container->getLocator()->contentNavigation()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addNavigationStorageClient(Container $container): Container
    {
        $container->set(static::CLIENT_NAVIGATION_STORAGE, function (Container $container) {
            return new ContentNavigationWidgetToNavigationStorageClientBridge(
                $container->getLocator()->navigationStorage()->client()
            );
        });

        return $container;
    }
}
