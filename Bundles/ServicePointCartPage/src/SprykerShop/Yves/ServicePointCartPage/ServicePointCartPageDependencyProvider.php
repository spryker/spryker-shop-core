<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointCartPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\ServicePointCartPage\Dependency\Client\ServicePointCartPageToServicePointCartClientBridge;

/**
 * @method \SprykerShop\Yves\ServicePointCartPage\ServicePointCartPageConfig getConfig()
 */
class ServicePointCartPageDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_SERVICE_POINT_CART = 'CLIENT_SERVICE_POINT_CART';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addServicePointCartClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addServicePointCartClient(Container $container): Container
    {
        $container->set(static::CLIENT_SERVICE_POINT_CART, function (Container $container) {
            return new ServicePointCartPageToServicePointCartClientBridge(
                $container->getLocator()->servicePointCart()->client(),
            );
        });

        return $container;
    }
}
