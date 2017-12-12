<?php
/**
 * Copyright © 2017-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget\Plugin\ShopLayout;

use Spryker\Yves\Kernel\Widget\AbstractWidgetPlugin;
use SprykerShop\Yves\CurrencyWidget\CurrencyWidgetFactory;
use SprykerShop\Yves\ShopLayout\Dependency\Plugin\CurrencyWidget\CurrencyWidgetPluginInterface;

/**
 * Class CurrencyWidgetPlugin
 *
 * @method CurrencyWidgetFactory getFactory()
 */
class CurrencyWidgetPlugin extends AbstractWidgetPlugin implements CurrencyWidgetPluginInterface
{
    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->addParameter('currencies', $this->getCurrencies())
            ->addParameter('currentCurrency', $this->getCurrentCurrency());
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getName(): string
    {
        return static::NAME;
    }

    /**
     * @api
     *
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CurrencyWidget/_partial/_currency_switcher.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    protected function getCurrencies()
    {
        $currencyBuilder = $this->getFactory()->createCurrencyBuilder();
        $availableCurrencyCodes = $this->getFactory()->getStore()->getCurrencyIsoCodes();

        $currencies = [];
        foreach ($availableCurrencyCodes as $currency) {
            $currencies[$currency] = $currencyBuilder->fromIsoCode($currency);
        }

        return $currencies;
    }

    /**
     * @return string
     */
    protected function getCurrentCurrency()
    {
        $currentCurrencyIsoCode = $this->getFactory()->createCurrencyPersistence()->getCurrentCurrencyIsoCode();

        if (!$currentCurrencyIsoCode) {
            return $this->getFactory()->getStore()->getCurrencyIsoCode();
        }
        return $currentCurrencyIsoCode;
    }
}
