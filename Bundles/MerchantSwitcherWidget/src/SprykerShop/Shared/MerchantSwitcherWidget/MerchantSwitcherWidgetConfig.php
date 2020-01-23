<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Spryker Marketplace License Agreement. See LICENSE file.
 */

namespace SprykerShop\Shared\MerchantSwitcherWidget;

use Spryker\Shared\Kernel\AbstractBundleConfig;

class MerchantSwitcherWidgetConfig extends AbstractBundleConfig
{
    /**
     * Specification:
     * - Identifier key for saving a selected merchant value to cookie.
     *
     * @api
     */
    public const MERCHANT_SELECTOR_COOKIE_IDENTIFIER = 'merchant_switcher_selector_merchant_reference';
}
