<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget\Cookie;

interface MerchantCookieInterface
{
    /**
     * @return string
     */
    public function getMerchantSelectorCookieIdentifier(): string;

    /**
     * @param string $selectedMerchantReference
     *
     * @return void
     */
    public function setMerchantSelectorCookieIdentifier(string $selectedMerchantReference): void;

    /**
     * @return void
     */
    public function removeMerchantSelectorCookieIdentifier(): void;
}
