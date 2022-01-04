<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MoneyWidget\Plugin\Twig;

use Spryker\Service\Container\ContainerInterface;
use Spryker\Shared\TwigExtension\Dependency\Plugin\TwigPluginInterface;
use Spryker\Yves\Kernel\AbstractPlugin;
use Twig\Environment;
use Twig\TwigFunction;

/**
 * @method \SprykerShop\Yves\MoneyWidget\MoneyWidgetFactory getFactory()
 */
class MoneyWidgetTwigPlugin extends AbstractPlugin implements TwigPluginInterface
{
    /**
     * @var string
     */
    protected const FUNCTION_NAME_MONEY_SYMBOL = 'moneySymbol';

    /**
     * @var string
     */
    protected const FUNCTION_NAME_CURRENCY_ISO_CODE = 'currencyIsoCode';

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
        $twig = $this->addTwigFunctions($twig);

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
        $twig->addFunction($this->getCurrencyIsoCodeFunction());

        return $twig;
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
     * @deprecated Use {@link \SprykerShop\Yves\MoneyWidget\Widget\CurrencyIsoCodeWidget} instead.
     *
     * @return \Twig\TwigFunction
     */
    protected function getCurrencyIsoCodeFunction(): TwigFunction
    {
        return new TwigFunction(static::FUNCTION_NAME_CURRENCY_ISO_CODE, function () {
            return $this->getFactory()->getCurrencyPlugin()->getCurrent()->getCode();
        });
    }
}
