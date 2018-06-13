<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Client\BusinessOnBehalfWidget\Dependency\Client;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CustomerTransfer;

interface BusinessOnBehalfWidgetToBusinessOnBehalfClientInterface
{
    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserCollectionTransfer
     */
    public function findActiveCompanyUsersByCustomerId(CustomerTransfer $customerTransfer): CompanyUserCollectionTransfer;
}
