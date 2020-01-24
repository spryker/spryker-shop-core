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
    /**
     * @return bool
     */
    public function isEnableMerchantSwitcher(): bool
    {
        return $this->getSharedConfig()->isEnableMerchantSwitcher();
    }
}
