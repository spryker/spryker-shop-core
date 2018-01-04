<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\NavigationWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;

class NavigationWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_NAVIGATION_STORAGE = 'CLIENT_NAVIGATION_STORAGE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addNavigationStorageClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addNavigationStorageClient($container)
    {
        $container[static::CLIENT_NAVIGATION_STORAGE] = function (Container $container) {
            return $container->getLocator()->navigationStorage()->client();
        };

        return $container;
    }
}
