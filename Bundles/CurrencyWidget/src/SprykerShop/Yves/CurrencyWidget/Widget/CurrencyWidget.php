<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget\Plugin\ShopUi;

use Spryker\Yves\Kernel\Widget\AbstractWidget;

/**
 * @method \SprykerShop\Yves\CurrencyWidget\CurrencyWidgetFactory getFactory()
 */
class CurrencyWidget extends AbstractWidget
{
    public function __construct()
    {
        $this->addParameter('currencies', $this->getCurrencies())
            ->addParameter('currentCurrency', $this->getCurrentCurrency());
    }

    /**
     * @return string
     */
    public static function getName(): string
    {
        return 'CurrencyWidgetPlugin';
    }

    /**
     * @return string
     */
    public static function getTemplate(): string
    {
        return '@CurrencyWidget/views/currency-switcher/currency-switcher.twig';
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    protected function getCurrencies()
    {
        $currencyClient = $this->getFactory()->getCurrencyClient();
        $availableCurrencyCodes = $this->getFactory()->getStore()->getCurrencyIsoCodes();

        $currencies = [];
        foreach ($availableCurrencyCodes as $currency) {
            $currencies[$currency] = $currencyClient->fromIsoCode($currency);
        }

        return $currencies;
    }

    /**
     * @return string
     */
    protected function getCurrentCurrency()
    {
        $currentCurrencyIsoCode = $this->getFactory()->getCurrencyClient()->getCurrent()->getCode();

        if (!$currentCurrencyIsoCode) {
            return $this->getFactory()->getStore()->getCurrencyIsoCode();
        }

        return $currentCurrencyIsoCode;
    }
}
