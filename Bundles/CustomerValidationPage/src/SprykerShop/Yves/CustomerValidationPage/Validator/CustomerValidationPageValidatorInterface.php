<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerValidationPage\Validator;

use Generated\Shared\Transfer\InvalidatedCustomerTransfer;

interface CustomerValidationPageValidatorInterface
{
    /**
     * @param \Generated\Shared\Transfer\InvalidatedCustomerTransfer $invalidatedCustomerTransfer
     *
     * @return bool
     */
    public function isCustomerValid(InvalidatedCustomerTransfer $invalidatedCustomerTransfer): bool;
}
