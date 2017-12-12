<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Dependency\Client;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface CheckoutPageToCustomerClientInterface
{
    /**
     * @return void
     */
    public function markCustomerAsDirty();

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer();

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function getAddress(AddressTransfer $addressTransfer);

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function findCustomerById(CustomerTransfer $customerTransfer);
}
