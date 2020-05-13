<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\MerchantSwitcher;

interface MerchantSwitcherInterface
{
    /**
     * @param string $merchantReference
     *
     * @return void
     */
    public function switchMerchantInQuote(string $merchantReference): void;
}
