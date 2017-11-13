<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CurrencyWidget\Dependency;

interface CurrencyPostChangePluginInterface
{
    /**
     * @param string $currencyCode
     *
     * @return string
     */
    public function execute($currencyCode);
}
