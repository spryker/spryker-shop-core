<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MoneyWidget\Plugin\Twig;

use Generated\Shared\Transfer\MoneyTransfer;
use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\MoneyWidget\Exception\WrongMoneyValueTypeException;
use Twig\Environment;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\MoneyWidget\MoneyWidgetFactory getFactory()
 */
class MoneyTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    protected const FUNCTION_NAME_MONEY_SYMBOL = 'moneySymbol';
    protected const FILTER_NAME_MONEY = 'money';
    protected const FILTER_NAME_MONEY_RAW = 'moneyRaw';

    protected const WRONG_MONEY_TYPE_ERROR_MESSAGE = 'Argument 1 passed to %s::getMoneyTransfer() must be of the type integer, string or float, %s given.';

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Twig\Environment $twig
     * @param \Spryker\Service\Container\ContainerInterface $container
     *
     * @return \Twig\Environment
     */
    public function extend(Environment $twig, ContainerInterface $container): Environment
    {
        $twig = $this->addTwigFilters($twig);
        $twig = $this->addTwigFunctions($twig);

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFilters(Environment $twig): Environment
    {
        $twig->addFilter($this->getMoneyFilter());
        $twig->addFilter($this->getMoneyRawFilter());

        return $twig;
    }

    /**
     * @param \Twig\Environment $twig
     *
     * @return \Twig\Environment
     */
    protected function addTwigFunctions(Environment $twig): Environment
    {
        $twig->addFunction($this->getMoneySymbolFunction());

        return $twig;
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function getMoneyFilter(): TwigFilter
    {
        return new TwigFilter(static::FILTER_NAME_MONEY, function ($money, bool $withSymbol = true, ?string $isoCode = null) {
            if ($money === null) {
                return $money;
            }

            if (!$money instanceof MoneyTransfer) {
                $money = $this->getMoneyTransfer($money, $isoCode);
            }

            $formatterType = MoneyFormatterCollection::FORMATTER_WITHOUT_SYMBOL;
            if ($withSymbol) {
                $formatterType = MoneyFormatterCollection::FORMATTER_WITH_SYMBOL;
            }

            return $this->getFactory()
                ->createMoneyFormatter()
                ->format($money, $formatterType);
        });
    }

    /**
     * @return \Twig\TwigFilter
     */
    protected function getMoneyRawFilter(): TwigFilter
    {
        return new TwigFilter(static::FILTER_NAME_MONEY_RAW, function ($money, $isoCode = null) {
            if ($money === null) {
                return null;
            }
            if (!$money instanceof MoneyTransfer) {
                $money = $this->getMoneyTransfer($money, $isoCode);
            }

            return $this->getFactory()
                ->createIntegerToDecimalConverter()
                ->convert((int)$money->getAmount());
        });
    }

    /**
     * @return \Twig\TwigFunction
     */
    protected function getMoneySymbolFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_MONEY_SYMBOL, function (?string $isoCode = null) {
            if ($isoCode === null) {
                return '';
            }

            return $this->getFactory()->getCurrencyPlugin()->fromIsoCode($isoCode);
        });
    }

    /**
     * @param mixed $money
     * @param string|null $isoCode
     *
     * @throws \SprykerShop\Yves\MoneyWidget\Exception\WrongMoneyValueTypeException
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function getMoneyTransfer($money, ?string $isoCode = null): MoneyTransfer
    {
        $moneyBuilder = $this->getFactory()->createMoneyBuilder();

        if (is_int($money)) {
            return $moneyBuilder->fromInteger($money, $isoCode);
        }

        if (is_string($money)) {
            return $moneyBuilder->fromString($money, $isoCode);
        }

        if (is_float($money)) {
            return $moneyBuilder->fromFloat($money, $isoCode);
        }

        throw new WrongMoneyValueTypeException(sprintf(static::WRONG_MONEY_TYPE_ERROR_MESSAGE, static::class, gettype($money)));
    }
}
