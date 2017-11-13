<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget;

use Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationBridge;
use Spryker\Shared\Kernel\Store;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CurrencyWidget\Dependency\Client\CurrencyToSessionBridge;
use SprykerShop\Yves\CurrencyWidget\Dependency\Client\CurrencyWidgetToCartClientBridge;
use Symfony\Component\Intl\Intl;

class CurrencyWidgetDependencyProvider extends AbstractBundleDependencyProvider
{

    const STORE = 'STORE';
    const INTERNATIONALIZATION = 'INTERNATIONALIZATION';
    const CLIENT_SESSION = 'CLIENT_SESSION';
    const CLIENT_CART = 'CLIENT_CART';
    const CURRENCY_POST_CHANGE_PLUGINS = 'CURRENCY_POST_CHANGE_PLUGINS';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addInternationalization($container);
        $container = $this->addSessionClient($container);
        $container = $this->addCartClient($container);
        $container = $this->addCurrencyPostChangePlugins($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addStore(Container $container)
    {
        $container[static::STORE] = function () {
            return Store::getInstance();
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addInternationalization(Container $container)
    {
        $container[static::INTERNATIONALIZATION] = function () {
            $currencyToInternationalizationBridge = new CurrencyToInternationalizationBridge(
                Intl::getCurrencyBundle()
            );

            return $currencyToInternationalizationBridge;
        };

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addSessionClient(Container $container)
    {
        $container[static::CLIENT_SESSION] = function (Container $container) {
            return new CurrencyToSessionBridge($container->getLocator()->session()->client());
        };

        return $container;
    }
    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCurrencyPostChangePlugins(Container $container)
    {
        $container[static::CURRENCY_POST_CHANGE_PLUGINS] = function () {
            return $this->getCurrencyPostChangePlugins();
        };

        return $container;
    }

    /**
     * @return \Spryker\Yves\Currency\Dependency\CurrencyPostChangePluginInterface[]
     */
    protected function getCurrencyPostChangePlugins()
    {
        return [];
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCartClient(Container $container)
    {
        $container[static::CLIENT_CART] = function (Container $container) {
            return new CurrencyWidgetToCartClientBridge($container->getLocator()->cart()->client());
        };

        return $container;
    }
}
