<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget\Plugin;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\CurrencyWidget\CurrencyChange\CurrencyPostChangePluginExecutorInterface;

/**
 *
 * @method \SprykerShop\Yves\CurrencyWidget\CurrencyWidgetFactory getFactory()
 */
class CurrencyChangeRebuildCartPlugin extends AbstractPlugin implements CurrencyPostChangePluginExecutorInterface
{
    /**
     * {@inheritdoc}
     *
     * @api
     *
     * @param string $currencyIsoCode
     *
     * @return void
     */
    public function execute($currencyIsoCode)
    {
        $cartClient = $this->getFactory()->getCartClient();

        $quoteTransfer = $cartClient->getQuote();
        if (count($quoteTransfer->getItems()) > 0) {
            $cartClient->reloadItems();
        }
    }
}
