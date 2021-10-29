<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MoneyWidget;

use Money\Currencies\ISOCurrencies;
use Money\Parser\IntlMoneyParser;
use NumberFormatter;
use Spryker\Shared\Money\Dependency\Parser\MoneyToParserBridge;
use Spryker\Yves\Kernel\AbstractBundleDependencyProvider;
use Spryker\Yves\Kernel\Container;
use SprykerShop\Yves\CurrencyWidget\Plugin\CurrencyPlugin;

class MoneyWidgetDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const CLIENT_LOCALE = 'CLIENT_LOCALE';

    /**
     * @var string
     */
    public const MONEY_PARSER = 'MONEY_PARSER';

    /**
     * @var string
     */
    public const CURRENCY_PLUGIN = 'CURRENCY_PLUGIN';

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    public function provideDependencies(Container $container)
    {
        $container = $this->addMoneyParser($container);
        $container = $this->addCurrencyPlugin($container);

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Spryker\Yves\Kernel\Container
     */
    protected function addMoneyParser(Container $container)
    {
        $container->set(static::MONEY_PARSER, function ($container) {
            $moneyToParserBridge = new MoneyToParserBridge($this->getIntlMoneyParser($container));

            return $moneyToParserBridge;
        });

        return $container;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \Money\Parser\IntlMoneyParser
     */
    protected function getIntlMoneyParser(Container $container)
    {
        $numberFormatter = $this->getNumberFormatter($container);
        $currencies = $this->getIsoCurrencies();
        $intlMoneyParser = new IntlMoneyParser($numberFormatter, $currencies);

        return $intlMoneyParser;
    }

    /**
     * @param \Spryker\Yves\Kernel\Container $container
     *
     * @return \NumberFormatter
     */
    protected function getNumberFormatter(Container $container)
    {
        $numberFormatter = new NumberFormatter(
            $container->getLocator()->locale()->client()->getCurrentLocale(),
            NumberFormatter::CURRENCY,
        );

        return $numberFormatter;
    }

    /**
     * @return \Money\Currencies\ISOCurrencies
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
        $container->set(static::CURRENCY_PLUGIN, function () {
            return $this->getCurrencyPlugin();
        });

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
