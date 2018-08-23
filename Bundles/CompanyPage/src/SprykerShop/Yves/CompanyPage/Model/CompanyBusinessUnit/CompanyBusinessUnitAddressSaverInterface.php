<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;

interface CompanyBusinessUnitAddressSaverInterface
{
    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function saveAddress(array $data): CompanyUnitAddressTransfer;
}
