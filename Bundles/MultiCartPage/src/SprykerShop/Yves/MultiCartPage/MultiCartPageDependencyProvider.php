<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MultiCartPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\MultiCartPage\Dependency\Client\MultiCartPageToMultiCartClientBridge;

class MultiCartPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_MULTI_CART = 'CLIENT_MULTI_CART';
    public const PLUGINS_CART_DELETE_COMPANY_USERS_LIST_WIDGET = 'PLUGINS_CART_DELETE_COMPANY_USERS_LIST_WIDGET';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return array|\Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addMultiCartClient($container);
        $container = $this->addCartDeleteCompanyUsersListWidgetPlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMultiCartClient($container): Container
    {
        $container[static::CLIENT_MULTI_CART] = function (Container $container) {
            return new MultiCartPageToMultiCartClientBridge($container->getLocator()->multiCart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartDeleteCompanyUsersListWidgetPlugins(Container $container): Container
    {
        $container[static::PLUGINS_CART_DELETE_COMPANY_USERS_LIST_WIDGET] = function (Container $container) {
            return $this->getCartDeleteCompanyUsersListWidgetPlugins();
        };

        return $container;
    }

    /**
     * @return string[]
     */
    protected function getCartDeleteCompanyUsersListWidgetPlugins(): array
    {
        return [];
    }
}
