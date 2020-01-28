<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\MerchantSwitcherWidget;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class MerchantSwitcherWidgetConfig extends AbstractBundleConfig
{
    protected const ENABLE_MERCHANT_SWITCHER = true;

    /**
     * Specification:
     * - Enables/disables merchant switcher functionality.
     *
     * @api
     *
     * @return bool
     */
    public function isMerchantSwitcherEnabled(): bool
    {
        return static::ENABLE_MERCHANT_SWITCHER;
    }
}
