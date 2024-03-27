<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Form\DataProvider;

use SprykerShop\Yves\MerchantRelationshipPage\Form\MerchantRelationshipSearchForm;
use SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageConfig;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyBusinessUnitReaderInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyUserReaderInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\MerchantSearchReaderInterface;

class MerchantRelationshipSearchFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\MerchantRelationshipPage\Reader\MerchantSearchReaderInterface
     */
    protected MerchantSearchReaderInterface $merchantSearchReader;

    /**
     * @var \SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyBusinessUnitReaderInterface
     */
    protected CompanyBusinessUnitReaderInterface $companyBusinessUnitReader;

    /**
     * @var \SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyUserReaderInterface
     */
    protected CompanyUserReaderInterface $companyUserReader;

    /**
     * @var \SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageConfig
     */
    protected MerchantRelationshipPageConfig $merchantRelationshipPageConfig;

    /**
     * @param \SprykerShop\Yves\MerchantRelationshipPage\Reader\MerchantSearchReaderInterface $merchantSearchReader
     * @param \SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyBusinessUnitReaderInterface $companyBusinessUnitReader
     * @param \SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyUserReaderInterface $companyUserReader
     * @param \SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageConfig $merchantRelationshipPageConfig
     */
    public function __construct(
        MerchantSearchReaderInterface $merchantSearchReader,
        CompanyBusinessUnitReaderInterface $companyBusinessUnitReader,
        CompanyUserReaderInterface $companyUserReader,
        MerchantRelationshipPageConfig $merchantRelationshipPageConfig
    ) {
        $this->merchantSearchReader = $merchantSearchReader;
        $this->companyBusinessUnitReader = $companyBusinessUnitReader;
        $this->companyUserReader = $companyUserReader;
        $this->merchantRelationshipPageConfig = $merchantRelationshipPageConfig;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            MerchantRelationshipSearchForm::OPTION_MERCHANT_CHOICES => $this->getMerchantChoices(),
            MerchantRelationshipSearchForm::OPTION_BUSINESS_UNIT_CHOICES => $this->getCompanyBusinessUnitChoices(),
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function getMerchantChoices(): array
    {
        $merchants = [];
        if (!$this->merchantRelationshipPageConfig->isFilterByMerchantEnabledForMerchantRelationshipTable()) {
            return $merchants;
        }

        $merchantSearchTransfers = $this->merchantSearchReader->getMerchantSearchTransferIndexedByMerchantReference();

        foreach ($merchantSearchTransfers as $merchantSearchTransfer) {
            $merchants[$merchantSearchTransfer->getIdMerchantOrFail()] = $merchantSearchTransfer->getNameOrFail();
        }

        return $merchants;
    }

    /**
     * @return array<int, string>
     */
    protected function getCompanyBusinessUnitChoices(): array
    {
        $businessUnitsChoices = [];
        $companyBusinessUnitTransfers = $this->companyBusinessUnitReader->getCompanyBusinessUnitsIndexedByIdCompanyBusinessUnit(
            $this->companyUserReader->getCurrentCompanyUser()->getFkCompanyOrFail(),
        );

        foreach ($companyBusinessUnitTransfers as $idCompanyBusinessUnit => $companyBusinessUnitTransfer) {
            $businessUnitsChoices[$idCompanyBusinessUnit] = sprintf(
                '%s (ID: %d)',
                $companyBusinessUnitTransfer->getNameOrFail(),
                $idCompanyBusinessUnit,
            );
        }

        return $businessUnitsChoices;
    }
}
