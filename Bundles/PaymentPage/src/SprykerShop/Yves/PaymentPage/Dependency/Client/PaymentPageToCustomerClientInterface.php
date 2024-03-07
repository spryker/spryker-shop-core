<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\PaymentPage\Dependency\Client;

use Generated\Shared\Transfer\CustomerTransfer;

interface PaymentPageToCustomerClientInterface
{
    /**
     * @return void
     */
    public function markCustomerAsDirty(): void;

    /**
     * @return \Generated\Shared\Transfer\CustomerTransfer|null
     */
    public function getCustomer(): ?CustomerTransfer;
}
