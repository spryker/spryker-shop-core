<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesConfigurableBundleWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\SalesConfigurableBundleWidget\Dependency\Client\SalesConfigurableBundleWidgetToMessengerClientBridge;

class SalesConfigurableBundleWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MESSENGER = 'CLIENT_MESSENGER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->addMessengerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMessengerClient(Container $container): Container
    {
        $container->set(static::CLIENT_MESSENGER, function (Container $container): SalesConfigurableBundleWidgetToMessengerClientBridge {
            return new SalesConfigurableBundleWidgetToMessengerClientBridge(
                $container->getLocator()->messenger()->client()
            );
        });

        return $container;
    }
}
