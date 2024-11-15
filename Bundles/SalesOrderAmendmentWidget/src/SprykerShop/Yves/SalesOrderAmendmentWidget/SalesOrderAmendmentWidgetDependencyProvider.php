<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SalesOrderAmendmentWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToCartReorderClientBridge;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToCustomerClientBridge;
use SprykerShop\Yves\SalesOrderAmendmentWidget\Dependency\Client\SalesOrderAmendmentWidgetToZedRequestClientBridge;

class SalesOrderAmendmentWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_ZED_REQUEST = 'CLIENT_ZED_REQUEST';

    /**
     * @var string
     */
    public const CLIENT_CUSTOMER = 'CLIENT_CUSTOMER';

    /**
     * @var string
     */
    public const CLIENT_CART_REORDER = 'CLIENT_CART_REORDER';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container): Container
    {
        $container = parent::provideDependencies($container);
        $container = $this->addCustomerClient($container);
        $container = $this->addZedRequestClient($container);
        $container = $this->addCartReorderClient($container);

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
            return new SalesOrderAmendmentWidgetToCustomerClientBridge(
                $container->getLocator()->customer()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addZedRequestClient(Container $container): Container
    {
        $container->set(static::CLIENT_ZED_REQUEST, function (Container $container) {
            return new SalesOrderAmendmentWidgetToZedRequestClientBridge(
                $container->getLocator()->zedRequest()->client(),
            );
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartReorderClient(Container $container): Container
    {
        $container->set(static::CLIENT_CART_REORDER, function (Container $container) {
            return new SalesOrderAmendmentWidgetToCartReorderClientBridge(
                $container->getLocator()->cartReorder()->client(),
            );
        });

        return $container;
    }
}
