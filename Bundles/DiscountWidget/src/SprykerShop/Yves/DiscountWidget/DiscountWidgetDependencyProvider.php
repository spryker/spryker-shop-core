<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientBridge;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCartClientBridge;

class DiscountWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    const CLIENT_CALCULATION = 'CLIENT_CALCULATION';
    const CLIENT_CART = 'CLIENT_CART';
    const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCalculationClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addApplication($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCalculationClient(Container $container): Container
    {
        $container[self::CLIENT_CALCULATION] = function (Container $container) {
            return new DiscountWidgetToCalculationClientBridge($container->getLocator()->calculation()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container): Container
    {
        $container[self::CLIENT_CART] = function (Container $container) {
            return new DiscountWidgetToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addApplication(Container $container): Container
    {
        $container[self::PLUGIN_APPLICATION] = function () {
            $pimplePlugin = new Pimple();
            return $pimplePlugin->getApplication();
        };

        return $container;
    }
}
