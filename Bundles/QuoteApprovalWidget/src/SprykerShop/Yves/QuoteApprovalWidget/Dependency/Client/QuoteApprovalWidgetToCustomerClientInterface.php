<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteApprovalWidget\Dependency\Client;

use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface QuoteApprovalWidgetToCustomerClientInterface
{
    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer();

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    public function findCustomerByReference(CustomerTransfer $customerTransfer): CustomerResponseTransfer;
}
