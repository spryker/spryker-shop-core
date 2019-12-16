<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\BusinessOnBehalfWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\BusinessOnBehalfWidget\Dependency\Client\BusinessOnBehalfWidgetToBusinessOnBehalfClientBridge;
use SprykerShop\Yves\BusinessOnBehalfWidget\Dependency\Client\BusinessOnBehalfWidgetToCustomerClientBridge;

class BusinessOnBehalfWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_BUSINESS_ON_BEHALF = 'CLIENT_BUSINESS_ON_BEHALF';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = $this->provideCustomerClient($container);
        $container = $this->provideBusinessOnBehalfClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new BusinessOnBehalfWidgetToCustomerClientBridge($container->getLocator()->customer()->client());
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function provideBusinessOnBehalfClient(Container $container): Container
    {
        $container->set(static::CLIENT_BUSINESS_ON_BEHALF, function (Container $container) {
            return new BusinessOnBehalfWidgetToBusinessOnBehalfClientBridge($container->getLocator()->businessOnBehalf()->client());
        });

        return $container;
    }
}
