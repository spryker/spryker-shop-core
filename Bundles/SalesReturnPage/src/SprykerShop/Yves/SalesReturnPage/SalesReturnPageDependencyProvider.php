<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesReturnPage;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToCustomerClientBridge;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesClientBridge;
use SprykerShop\Yves\SalesReturnPage\Dependency\Client\SalesReturnPageToSalesReturnClientBridge;

class SalesReturnPageDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';
    public const CLIENT_SALES_RETURN = 'CLIENT_SALES_RETURN';
    public const CLIENT_SALES = 'CLIENT_SALES';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);

        $container = $this->addSalesReturnClient($container);
        $container = $this->addSalesClient($container);
        $container = $this->addCustomerClient($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSalesReturnClient(Container $container): Container
    {
        $container->set(static::CLIENT_SALES_RETURN, function (Container $container) {
            return new SalesReturnPageToSalesReturnClientBridge(
                $container->getLocator()->salesReturn()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSalesClient(Container $container): Container
    {
        $container->set(static::CLIENT_SALES, function (Container $container) {
            return new SalesReturnPageToSalesClientBridge(
                $container->getLocator()->sales()->client()
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCustomerClient(Container $container): Container
    {
        $container->set(static::CLIENT_CUSTOMER, function (Container $container) {
            return new SalesReturnPageToCustomerClientBridge(
                $container->getLocator()->customer()->client()
            );
        });

        return $container;
    }
}
