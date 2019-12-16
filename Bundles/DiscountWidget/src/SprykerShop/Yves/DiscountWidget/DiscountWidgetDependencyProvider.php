<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\DiscountWidget;

use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use Spryker\Yves\Kernel\Plugin\Pimple;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToCalculationClientBridge;
use SprykerShop\Yves\DiscountWidget\Dependency\Client\DiscountWidgetToQuoteClientBridge;

class DiscountWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const CLIENT_CALCULATION = 'CLIENT_CALCULATION';
    public const PLUGIN_APPLICATION = 'PLUGIN_APPLICATION';
    public const CLIENT_QUOTE = 'CLIENT_QUOTE';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addCalculationClient($container);
        $container = $this->addQuoteClient($container);
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
    protected function addQuoteClient(Container $container): Container
    {
        $container[self::CLIENT_QUOTE] = function (Container $container) {
            return new DiscountWidgetToQuoteClientBridge($container->getLocator()->quote()->client());
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
