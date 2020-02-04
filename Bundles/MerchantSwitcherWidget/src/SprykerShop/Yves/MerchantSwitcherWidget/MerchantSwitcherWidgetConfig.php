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
    public const MERCHANT_SELECTOR_COOKIE_IDENTIFIER = 'merchant_switcher_selector-merchant_reference';
    public const COOKIE_TIME_EXPIRATION = 10 * 365 * 24 * 60 * 60;

    /**
     * @return bool
     */
    public function isMerchantSwitcherEnabled(): bool
    {
        return $this->getSharedConfig()->isMerchantSwitcherEnabled();
    }
}
