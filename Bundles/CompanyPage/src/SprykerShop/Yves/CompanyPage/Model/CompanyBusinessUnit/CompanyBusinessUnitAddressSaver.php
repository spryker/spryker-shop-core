<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Model\CompanyBusinessUnit;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface;

class CompanyBusinessUnitAddressSaver implements CompanyBusinessUnitAddressSaverInterface
{
    /**
     * @var \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface
     */
    protected $companyUnitAddressClient;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCompanyUnitAddressClientInterface $companyUnitAddressClient
     */
    public function __construct(CompanyPageToCompanyUnitAddressClientInterface $companyUnitAddressClient)
    {
        $this->companyUnitAddressClient = $companyUnitAddressClient;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    public function saveAddress(array $data): CompanyUnitAddressTransfer
    {
        $addressTransfer = new CompanyUnitAddressTransfer();
        $addressTransfer->fromArray($data, true);

        $addressTransfer = $this->companyUnitAddressClient->createCompanyUnitAddress($addressTransfer);

        return $addressTransfer->getCompanyUnitAddressTransfer();
    }

    /**
     * @param int|null $idBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function createCompanyBusinessUnitCollectionTransfer(?int $idBusinessUnit = null): CompanyBusinessUnitCollectionTransfer
    {
        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();

        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit($idBusinessUnit);

        $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnitTransfer);

        return $companyBusinessUnitCollectionTransfer;
    }
}
