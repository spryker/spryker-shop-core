<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Mapper;

use Generated\Shared\Transfer\AddressTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

class CompanyUnitMapper implements CompanyUnitMapperInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer $addressTransfer
     *
     * @return \Generated\Shared\Transfer\AddressTransfer
     */
    public function mapCompanyUnitAddressTransferToAddressTransfer(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        AddressTransfer $addressTransfer
    ): AddressTransfer {
        return (new AddressTransfer())
            ->fromArray($companyUnitAddressTransfer->modifiedToArray(), true);
    }
}
