<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class CustomerPageConfig extends AbstractBundleConfig
{
    protected const CUSTOMER_PASSWORD_MIN_LENGTH = 8;

    /**
     * @return string
     */
    public function getYvesHost()
    {
        return $this->get(ApplicationConstants::HOST_YVES);
    }

    /**
     * @return int
     */
    public function getCustomerPasswordMinLength(): int
    {
        return static::CUSTOMER_PASSWORD_MIN_LENGTH;
    }
}
