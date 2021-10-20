<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MoneyWidget;

use Spryker\Shared\Money\Builder\MoneyBuilder;
use Spryker\Shared\Money\Converter\DecimalToIntegerConverter;
use Spryker\Shared\Money\Converter\IntegerToDecimalConverter;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithCurrency;
use Spryker\Shared\Money\Formatter\IntlMoneyFormatter\IntlMoneyFormatterWithoutCurrency;
use Spryker\Shared\Money\Formatter\MoneyFormatter;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Shared\Money\Mapper\TransferToMoneyMapper;
use Spryker\Shared\Money\Parser\Parser;
use Spryker\Yves\Kernel\AbstractFactory;
use SprykerShop\Yves\MoneyWidget\Mapper\MoneyToTransferMapper;

class MoneyWidgetFactory extends AbstractFactory
{
    /**
     * @return \Spryker\Shared\Money\Builder\MoneyBuilderInterface
     */
    public function createMoneyBuilder()
    {
        return new MoneyBuilder(
            $this->createMoneyToTransferMapper(),
            $this->createDecimalToIntegerConverter(),
            (string)$this->getCurrencyPlugin()->getCurrent()->getCode(),
        );
    }

    /**
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterWithTypeInterface
     */
    public function createMoneyFormatter()
    {
        return new MoneyFormatter(
            $this->createFormatterCollection(),
        );
    }

    /**
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterCollectionInterface
     */
    public function createFormatterCollection()
    {
        $moneyFormatterCollection = new MoneyFormatterCollection();
        $moneyFormatterCollection->addFormatter(
            $this->createIntlFormatterCurrency(),
            MoneyFormatterCollection::FORMATTER_WITH_SYMBOL,
        );

        $moneyFormatterCollection->addFormatter(
            $this->createIntlFormatterDecimal(),
            MoneyFormatterCollection::FORMATTER_WITHOUT_SYMBOL,
        );

        return $moneyFormatterCollection;
    }

    /**
     * @return \Spryker\Shared\Money\Parser\ParserInterface
     */
    public function createMoneyParser()
    {
        return new Parser(
            $this->getMoneyParser(),
            $this->createMoneyToTransferMapper(),
        );
    }

    /**
     * @return \Spryker\Shared\Money\Dependency\Parser\MoneyToParserInterface
     */
    public function getMoneyParser()
    {
        return $this->getProvidedDependency(MoneyWidgetDependencyProvider::MONEY_PARSER);
    }

    /**
     * @return \Spryker\Shared\Kernel\Store
     */
    public function getStore()
    {
        return $this->getProvidedDependency(MoneyWidgetDependencyProvider::STORE);
    }

    /**
     * @return \Spryker\Shared\Money\Mapper\MoneyToTransferMapperInterface
     */
    public function createMoneyToTransferMapper()
    {
        return new MoneyToTransferMapper(
            $this->getCurrencyPlugin(),
        );
    }

    /**
     * @return \SprykerShop\Yves\CurrencyWidget\Plugin\CurrencyPluginInterface
     */
    public function getCurrencyPlugin()
    {
        return $this->getProvidedDependency(MoneyWidgetDependencyProvider::CURRENCY_PLUGIN);
    }

    /**
     * @return \Spryker\Shared\Money\Mapper\TransferToMoneyMapperInterface
     */
    public function createTransferToMoneyMapper()
    {
        return new TransferToMoneyMapper();
    }

    /**
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterInterface
     */
    public function createIntlFormatterCurrency()
    {
        return new IntlMoneyFormatterWithCurrency(
            $this->createTransferToMoneyMapper(),
        );
    }

    /**
     * @return \Spryker\Shared\Money\Formatter\MoneyFormatterInterface
     */
    public function createIntlFormatterDecimal()
    {
        return new IntlMoneyFormatterWithoutCurrency(
            $this->createTransferToMoneyMapper(),
        );
    }

    /**
     * @return \Spryker\Shared\Money\Converter\IntegerToDecimalConverterInterface
     */
    public function createIntegerToDecimalConverter()
    {
        return new IntegerToDecimalConverter();
    }

    /**
     * @return \Spryker\Shared\Money\Converter\DecimalToIntegerConverterInterface
     */
    public function createDecimalToIntegerConverter()
    {
        return new DecimalToIntegerConverter();
    }
}
