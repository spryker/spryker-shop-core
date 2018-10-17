<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyWidget\Address;

use ArrayObject;

interface AddressHandlerInterface
{
    /**
     * @param string $formType
     *
     * @return array
     */
    public function getCustomerAddressesArray(string $formType): array;

    /**
     * @param string $formType
     *
     * @return array
     */
    public function getCompanyBusinessUnitAddressesArray(string $formType): array;

    /**
     * @return \ArrayObject|\Generated\Shared\Transfer\CompanyUnitAddressTransfer[]
     */
    public function getCompanyBusinessUnitAddresses(): ArrayObject;

    /**
     * @param string $formType
     *
     * @return array
     */
    public function getAvailableFullAddresses(string $formType): array;
}
