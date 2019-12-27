<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage;

use Spryker\Shared\Application\ApplicationConstants;
use Spryker\Shared\Customer\CustomerConstants;
use Spryker\Yves\Kernel\AbstractBundleConfig;

class CustomerPageConfig extends AbstractBundleConfig
{
    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::MIN_LENGTH_CUSTOMER_PASSWORD
     */
    protected const MIN_LENGTH_CUSTOMER_PASSWORD = 1;

    /**
     * @uses \Spryker\Zed\Customer\CustomerConfig::MAX_LENGTH_CUSTOMER_PASSWORD
     */
    protected const MAX_LENGTH_CUSTOMER_PASSWORD = 72;

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
        return static::MIN_LENGTH_CUSTOMER_PASSWORD;
    }

    /**
     * @return int
     */
    public function getCustomerPasswordMaxLength(): int
    {
        return static::MAX_LENGTH_CUSTOMER_PASSWORD;
    }

    /**
     * @return string
     */
    public function getAnonymousPattern(): string
    {
        return $this->get(CustomerConstants::CUSTOMER_ANONYMOUS_PATTERN);
    }

    /**
     * URL to redirect in case of login failure.
     * If return value is null - will be redirected to referer URL.
     *
     * @return string|null
     */
    public function getFailureLoginUrl(): ?string
    {
        return null;
    }
}
