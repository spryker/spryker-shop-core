<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyUser;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;

interface CompanyUserSaverInterface
{
    /**
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     * @param array<string, mixed> $formData
     * @param bool $isDefault
     *
     * @return void
     */
    public function saveCompanyUser(
        CompanyUserCollectionTransfer $companyUserCollectionTransfer,
        array $formData,
        bool $isDefault = false
    ): void;
}
