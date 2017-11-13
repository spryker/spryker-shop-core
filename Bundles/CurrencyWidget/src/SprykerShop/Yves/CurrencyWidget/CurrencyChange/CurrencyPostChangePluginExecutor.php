<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget\CurrencyChange;

class CurrencyPostChangePluginExecutor implements CurrencyPostChangePluginExecutorInterface
{
    /**
     * @var \SprykerShop\Yves\CurrencyWidget\Dependency\CurrencyPostChangePluginInterface[]
     */
    protected $currencyPostChangePlugins = [];

    /**
     * @param \SprykerShop\Yves\CurrencyWidget\Dependency\CurrencyPostChangePluginInterface[] $currencyPostChangePlugins
     */
    public function __construct(array $currencyPostChangePlugins)
    {
        $this->currencyPostChangePlugins = $currencyPostChangePlugins;
    }

    /**
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function execute($currencyIsoCode)
    {
        foreach ($this->currencyPostChangePlugins as $currencyPostChangePlugins) {
            $currencyPostChangePlugins->execute($currencyIsoCode);
        }
    }
}
