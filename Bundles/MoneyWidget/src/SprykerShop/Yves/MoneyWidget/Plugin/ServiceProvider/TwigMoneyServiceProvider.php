<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MoneyWidget\Plugin\ServiceProvider;

use Generated\Shared\Transfer\MoneyTransfer;
use Silex\Application;
use Silex\ServiceProviderInterface;
use Spryker\Shared\Money\Formatter\MoneyFormatterCollection;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * @deprecated Use `SprykerShop\Yves\MoneyWidget\Plugin\Twig\MoneyTwigPlugin` instead.
 *
 * @method \Spryker\Yves\Money\MoneyFactory getFactory()
 */
class TwigMoneyServiceProvider extends AbstractPlugin implements ServiceProviderInterface
{
    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function register(Application $app)
    {
        $app['twig'] = $app->share(
            $app->extend('twig', function (Environment $twig) {
                $twig->addFilter($this->getMoneyFilter());
                $twig->addFilter($this->getMoneyRawFilter());
                $twig->addFunction($this->getMoneySymbol());

                return $twig;
            })
        );
    }

    /**
     * @param \Silex\Application $app
     *
     * @return void
     */
    public function boot(Application $app)
    {
    }

    /**
     * @uses TwigMoneyServiceProvider::moneyFilterFunction()
     *
     * @return \Twig\TwigFilter
     */
    protected function getMoneyFilter()
    {
        return new TwigFilter('money', [$this, 'moneyFilterFunction']);
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer|int|string|float|null $money
     * @param bool $withSymbol
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer|string|null
     */
    public function moneyFilterFunction($money, $withSymbol = true, $isoCode = null)
    {
        if ($money === null) {
            return $money;
        }

        if (!($money instanceof MoneyTransfer)) {
            $money = $this->getMoneyTransfer($money, $isoCode);
        }

        if ($withSymbol) {
            return $this->getFactory()->createMoneyFormatter()->format($money, MoneyFormatterCollection::FORMATTER_WITH_SYMBOL);
        }

        return $this->getFactory()
            ->createMoneyFormatter()
            ->format($money, MoneyFormatterCollection::FORMATTER_WITHOUT_SYMBOL);
    }

    /**
     * @uses TwigMoneyServiceProvider::moneyRawFilterFunction()
     *
     * @return \Twig\TwigFilter
     */
    protected function getMoneyRawFilter()
    {
        return new TwigFilter('moneyRaw', [$this, 'moneyRawFilterFunction']);
    }

    /**
     * @param \Generated\Shared\Transfer\MoneyTransfer|int|string|float|null $money
     * @param string|null $isoCode
     *
     * @return float|null
     */
    public function moneyRawFilterFunction($money, $isoCode = null)
    {
        if ($money === null) {
            return null;
        }
        if (!($money instanceof MoneyTransfer)) {
            $money = $this->getMoneyTransfer($money, $isoCode);
        }

        return $this->getFactory()
            ->createIntegerToDecimalConverter()
            ->convert((int)$money->getAmount());
    }

    /**
     * @uses TwigMoneyServiceProvider::moneySymbolFunction()
     *
     * @return \Twig\TwigFunction
     */
    protected function getMoneySymbol()
    {
        return new TwigFunction('moneySymbol', [$this, 'moneySymbolFunction']);
    }

    /**
     * @param string|null $isoCode
     *
     * @return string|null
     */
    public function moneySymbolFunction($isoCode = null)
    {
        $money = $this->getMoneyTransfer(100, $isoCode);
        if ($money->getCurrency() === null || $money->getCurrency()->getSymbol() === null) {
            return '';
        }

        return $money->getCurrency()->getSymbol();
    }

    /**
     * @param int|string|float $money
     * @param string|null $isoCode
     *
     * @return \Generated\Shared\Transfer\MoneyTransfer
     */
    protected function getMoneyTransfer($money, $isoCode = null)
    {
        $moneyFactory = $this->getFactory();

        if (is_int($money)) {
            $money = $moneyFactory->createMoneyBuilder()->fromInteger($money, $isoCode);
        }

        if (is_string($money)) {
            $money = $moneyFactory->createMoneyBuilder()->fromString($money, $isoCode);
        }

        if (is_float($money)) {
            $money = $moneyFactory->createMoneyBuilder()->fromFloat($money, $isoCode);
        }

        return $money;
    }
}
