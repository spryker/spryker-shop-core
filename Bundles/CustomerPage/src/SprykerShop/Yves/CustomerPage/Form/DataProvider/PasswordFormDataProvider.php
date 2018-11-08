<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form\DataProvider;

use SprykerShop\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Form\PasswordForm;

class PasswordFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\CustomerPageConfig
     */
    protected $customerPageConfig;

    /**
     * @param \SprykerShop\Yves\CustomerPage\CustomerPageConfig $customerPageConfig
     */
    public function __construct(CustomerPageConfig $customerPageConfig)
    {
        $this->customerPageConfig = $customerPageConfig;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return [
            PasswordForm::OPTION_MIN_LENGTH_CUSTOMER_PASSWORD => $this->customerPageConfig->getCustomerPasswordMinLength(),
        ];
    }
}
