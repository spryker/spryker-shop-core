<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Mapper;

use Generated\Shared\Transfer\AddressTransfer;

class CustomerMapper implements CustomerMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $customerAddressTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapCustomerAddressTransferToAddressTransfer(
        AddressTransfer $customerAddressTransfer,
        AddressTransfer $addressTransfer
    ): AddressTransfer {
        return $addressTransfer->fromArray($customerAddressTransfer->toArray());
    }
}
