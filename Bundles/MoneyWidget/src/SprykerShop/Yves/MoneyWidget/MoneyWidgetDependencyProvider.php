<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MoneyWidget;

use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use Spryker\Shared\Kernel\Store;
use Spryker\Shared\Money\Dependency\Parser\MoneyToParserBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CurrencyWidget\Plugin\CurrencyPlugin;

class MoneyWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    public const STORE = 'MONEY_PARSER';
    public const MONEY_PARSER = 'MONEY_PARSER';
    public const CURRENCY_PLUGIN = 'CURRENCY_PLUGIN';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addStore($container);
        $container = $this->addMoneyParser($container);
        $container = $this->addCurrencyPlugin($container);

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
            return $this->getStore();
        };

        return $container;
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    protected function getStore()
    {
        return Store::getInstance();
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyParser(Container $container)
    {
        $container[static::MONEY_PARSER] = function () {
            $moneyToParserBridge = new MoneyToParserBridge($this->getIntlMoneyParser());

            return $moneyToParserBridge;
        };

        return $container;
    }

    /**
     * @return \Money\Parser\IntlMoneyParser
     */
    protected function getIntlMoneyParser()
    {
        $numberFormatter = $this->getNumberFormatter();
        $currencies = $this->getIsoCurrencies();
        $intlMoneyParser = new IntlMoneyParser($numberFormatter, $currencies);

        return $intlMoneyParser;
    }

    /**
     * @return \NumberFormatter
     */
    protected function getNumberFormatter()
    {
        $numberFormatter = new NumberFormatter(
            $this->getStore()->getCurrentLocale(),
            NumberFormatter::CURRENCY
        );

        return $numberFormatter;
    }

    /**
     * @return \Money\Currencies\ISOCurrencies<mixed>
     */
    protected function getIsoCurrencies()
    {
        $isoCurrencies = new ISOCurrencies();

        return $isoCurrencies;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addCurrencyPlugin(Container $container)
    {
        $container[static::CURRENCY_PLUGIN] = function () {
            return $this->getCurrencyPlugin();
        };

        return $container;
    }

    /**
     * @return \SprykerShop\Yves\CurrencyWidget\Plugin\CurrencyPluginInterface
     */
    protected function getCurrencyPlugin()
    {
        return new CurrencyPlugin();
    }
}
