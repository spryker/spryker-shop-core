<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget;

use Spryker\Shared\Currency\Builder\CurrencyBuilder;
use Spryker\Shared\Currency\Persistence\CurrencyPersistence;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\CurrencyWidget\CurrencyChange\CurrencyPostChangePluginExecutor;

class CurrencyWidgetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Currency\Builder\CurrencyBuilderInterface
     */
    public function createCurrencyBuilder()
    {
        return new CurrencyBuilder(
            $this->getInternationalization(),
            $this->createCurrencyPersistence()->getCurrentCurrencyIsoCode()
        );
    }

    /**
     * @return \Spryker\Shared\Currency\Persistence\CurrencyPersistenceInterface
     */
    public function createCurrencyPersistence()
    {
        return new CurrencyPersistence($this->getSessionClient(), $this->getStore());
    }

    /**
     * @return \SprykerShop\Yves\CurrencyWidget\CurrencyChange\CurrencyPostChangePluginExecutorInterface
     */
    public function createCurrencyPostChangePluginExecutor()
    {
        return new CurrencyPostChangePluginExecutor($this->getCurrencyPostChangePlugins());
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Internationalization\CurrencyToInternationalizationInterface
     */
    protected function getInternationalization()
    {
        return $this->getProvidedDependency(CurrencyWidgetDependencyProvider::INTERNATIONALIZATION);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(CurrencyWidgetDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Shared\Currency\Dependency\Client\CurrencyToSessionInterface
     */
    protected function getSessionClient()
    {
        return $this->getProvidedDependency(CurrencyWidgetDependencyProvider::CLIENT_SESSION);
    }

    /**
     * @return \SprykerShop\Yves\CurrencyWidget\Dependency\Client\CurrencyWidgetToCartClientInterface
     */
    public function getCartClient()
    {
        return $this->getProvidedDependency(CurrencyWidgetDependencyProvider::CLIENT_CART);
    }

    /**
     * @return \SprykerShop\Yves\CurrencyWidget\Dependency\CurrencyPostChangePluginInterface[]
     */
    protected function getCurrencyPostChangePlugins()
    {
        return $this->getProvidedDependency(CurrencyWidgetDependencyProvider::CURRENCY_POST_CHANGE_PLUGINS);
    }
}
