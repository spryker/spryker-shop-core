<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPageExtension\Dependency\Plugin;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface AddressTransferExpanderPluginInterface
{
    /**
     * Specification:
     * - Expands address transfer with data from customer addresses fields.
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function expand(AddressTransfer $addressTransfer, ?CustomerTransfer $customerTransfer): AddressTransfer;
}
