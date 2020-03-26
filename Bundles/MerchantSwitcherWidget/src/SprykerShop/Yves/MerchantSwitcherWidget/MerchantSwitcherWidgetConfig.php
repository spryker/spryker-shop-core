<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantSwitcherWidget;

use Spryker\Yves\Kernel\AbstractBundleConfig;

/**
 * @method \SprykerShop\Shared\MerchantSwitcherWidget\MerchantSwitcherWidgetConfig getSharedConfig()
 */
class MerchantSwitcherWidgetConfig extends AbstractBundleConfig
{
    protected const MERCHANT_SELECTOR_COOKIE_IDENTIFIER = 'merchant_switcher_selector-merchant_reference';

    /**
     * Ten years in seconds.
     */
    protected const MERCHANT_SELECTOR_COOKIE_TIME_EXPIRATION = 315360000;

    /**
     * @api
     *
     * @return bool
     */
    public function isMerchantSwitcherEnabled(): bool
    {
        return $this->getSharedConfig()->isMerchantSwitcherEnabled();
    }

    /**
     * @api
     *
     * @return string
     */
    public function getMerchantSelectorCookieIdentifier(): string
    {
        return static::MERCHANT_SELECTOR_COOKIE_IDENTIFIER;
    }

    /**
     * @api
     *
     * @return int
     */
    public function getMerchantSelectorCookieTimeExpiration(): int
    {
        return static::MERCHANT_SELECTOR_COOKIE_TIME_EXPIRATION;
    }
}
