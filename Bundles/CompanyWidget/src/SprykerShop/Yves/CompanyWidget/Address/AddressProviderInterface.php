<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Address;

use Generated\Shared\Transfer\AddressTransfer;

interface AddressProviderInterface
{
    /**
     * @return bool
     */
    public function companyBusinessUnitAddressesExists(): bool;

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer[]
     */
    public function getIndexedCustomerAddressList(): array;

    /**
     * @return \Generated\Shared\Transfer\AddressTransfer[]
     */
    public function getIndexedCompanyBusinessUnitAddressList(): array;

    /**
     * @param \Generated\Shared\Transfer\AddressTransfer $formAddressTransfer
     * @param \Generated\Shared\Transfer\AddressTransfer[] $companyBusinessUnitAddresses
     *
     * @return \Generated\Shared\Transfer\AddressTransfer|null
     */
    public function findCurrentCompanyBusinessUnitAddress(AddressTransfer $formAddressTransfer, array $companyBusinessUnitAddresses): ?AddressTransfer;
}
