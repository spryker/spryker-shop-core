<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShopCmsSlot;

use Spryker\Yves\Kernel\AbstractBundleConfig;
use SprykerShop\Shared\ShopApplication\ShopApplicationConstants;

class ShopCmsSlotConfig extends AbstractBundleConfig
{
    /**
     * @return bool
     */
    public function isDebugModeEnabled(): bool
    {
        return $this->get(ShopApplicationConstants::ENABLE_APPLICATION_DEBUG, false);
    }
}
