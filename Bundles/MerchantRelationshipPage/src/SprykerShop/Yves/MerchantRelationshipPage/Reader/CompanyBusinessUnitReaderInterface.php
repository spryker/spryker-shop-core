<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Reader;

interface CompanyBusinessUnitReaderInterface
{
    /**
     * @param int $idCompany
     *
     * @return array<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer>
     */
    public function getCompanyBusinessUnitsIndexedByIdCompanyBusinessUnit(int $idCompany): array;
}
