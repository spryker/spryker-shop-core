<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Reader;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyBusinessUnitClientInterface;

class CompanyBusinessUnitReader implements CompanyBusinessUnitReaderInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyBusinessUnitClientInterface
     */
    protected MerchantRelationRequestPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient;

    /**
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
     */
    public function __construct(
        MerchantRelationRequestPageToCompanyBusinessUnitClientInterface $companyBusinessUnitClient
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
