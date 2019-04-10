<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PersistentCartSharePage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\PersistentCartSharePage\Dependency\Client\PersistentCartSharePageToPersistentCartShareClientBridge;

class PersistentCartSharePageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_PERSISTENT_CART_SHARE = 'CLIENT_PERSISTENT_CART_SHARE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addPersistentCartShareClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addPersistentCartShareClient(Container $container)
    {
        $container[static::CLIENT_PERSISTENT_CART_SHARE] = function (Container $container) {
            return new PersistentCartSharePageToPersistentCartShareClientBridge($container->getLocator()->persistentCartShare()->client());
        };

        return $container;
    }
}
