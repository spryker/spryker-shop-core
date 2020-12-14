<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SecurityBlockerPage;

use Spryker\Yves\Kernel\AbstractBundleConfig;

class SecurityBlockerPageConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Client\SecurityBlocker\SecurityBlockerConfig::SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE
     */
    public const SECURITY_BLOCKER_CUSTOMER_ENTITY_TYPE = 'customer';
}
