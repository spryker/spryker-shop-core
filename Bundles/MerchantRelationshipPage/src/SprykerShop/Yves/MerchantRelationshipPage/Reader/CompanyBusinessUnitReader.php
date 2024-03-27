<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Reader;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyBusinessUnitClientInterface;

class CompanyBusinessUnitReader implements CompanyBusinessUnitReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyBusinessUnitClientInterface
     */
    protected MerchantRelationshipPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient;

    /**
     * @param \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     */
    public function __construct(
        MerchantRelationshipPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
    ) {
        $this->companyBusinessUnitClient = $companyBusinessUnitClient;
    }

    /**
     * @param int $idCompany
     *
     * @return array<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer>
     */
    public function getCompanyBusinessUnitsIndexedByIdCompanyBusinessUnit(int $idCompany): array
    {
        $indexedCompanyBusinessUnits = [];
        $companyBusinessUnitCriteriaFilterTransfer = (new CompanyBusinessUnitCriteriaFilterTransfer())
            ->setIdCompany($idCompany);

        $companyBusinessUnitCollectionTransfer = $this->companyBusinessUnitClient
            ->getCompanyBusinessUnitCollection($companyBusinessUnitCriteriaFilterTransfer);

        foreach ($companyBusinessUnitCollectionTransfer->getCompanyBusinessUnits() as $companyBusinessUnitTransfer) {
            $indexedCompanyBusinessUnits[$companyBusinessUnitTransfer->getIdCompanyBusinessUnitOrFail()] = $companyBusinessUnitTransfer;
        }

        return $indexedCompanyBusinessUnits;
    }
}
