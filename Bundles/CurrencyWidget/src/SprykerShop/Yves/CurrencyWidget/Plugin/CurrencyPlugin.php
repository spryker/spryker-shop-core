<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;

/**
 * @method \SprykerShop\Yves\CurrencyWidget\CurrencyWidgetFactory getFactory()
 */
class CurrencyPlugin extends AbstractPlugin implements CurrencyPluginInterface
{
    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer|null
     */
    protected static $currentCurrencyTransfer;

    /**
     * @var \Generated\Shared\Transfer\CurrencyTransfer[]
     */
    protected static $currencyTransfersByIsoCode = [];

    /**
     * @param string $isoCode
     *
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function fromIsoCode($isoCode)
    {
        if (!isset(static::$currencyTransfersByIsoCode[$isoCode])) {
            static::$currencyTransfersByIsoCode[$isoCode] = $this->getFactory()->getCurrencyClient()->fromIsoCode($isoCode);
        }

        return static::$currencyTransfersByIsoCode[$isoCode];
    }

    /**
     * @return \Generated\Shared\Transfer\CurrencyTransfer
     */
    public function getCurrent()
    {
        if (static::$currentCurrencyTransfer === null) {
            static::$currentCurrencyTransfer = $this->getFactory()->getCurrencyClient()->getCurrent();
        }

        return static::$currentCurrencyTransfer;
    }
}
