<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Form\DataProvider;

use SprykerShop\Yves\MerchantRelationRequestPage\Form\MerchantRelationRequestSearchForm;
use SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyBusinessUnitReaderInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantSearchReaderInterface;

class MerchantRelationRequestSearchFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantSearchReaderInterface
     */
    protected MerchantSearchReaderInterface $merchantSearchReader;

    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyBusinessUnitReaderInterface
     */
    protected CompanyBusinessUnitReaderInterface $companyBusinessUnitReader;

    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface
     */
    protected CompanyUserReaderInterface $companyUserReader;

    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig
     */
    protected MerchantRelationRequestPageConfig $merchantRelationRequestPageConfig;

    /**
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantSearchReaderInterface $merchantSearchReader
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyBusinessUnitReaderInterface $companyBusinessUnitReader
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface $companyUserReader
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig $merchantRelationRequestPageConfig
     */
    public function __construct(
        MerchantSearchReaderInterface $merchantSearchReader,
        CompanyBusinessUnitReaderInterface $companyBusinessUnitReader,
        CompanyUserReaderInterface $companyUserReader,
        MerchantRelationRequestPageConfig $merchantRelationRequestPageConfig
    ) {
        $this->merchantSearchReader = $merchantSearchReader;
        $this->companyBusinessUnitReader = $companyBusinessUnitReader;
        $this->companyUserReader = $companyUserReader;
        $this->merchantRelationRequestPageConfig = $merchantRelationRequestPageConfig;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return [
            MerchantRelationRequestSearchForm::OPTION_MERCHANT_CHOICES => $this->getMerchantChoices(),
            MerchantRelationRequestSearchForm::OPTION_BUSINESS_UNIT_CHOICES => $this->getCompanyBusinessUnitChoices(),
            MerchantRelationRequestSearchForm::OPTION_STATUS_CHOICES => $this->getStatusChoices(),
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function getMerchantChoices(): array
    {
        $merchants = [];
        if (!$this->merchantRelationRequestPageConfig->isFilterByMerchantEnabledForMerchantRelationRequestTable()) {
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

    /**
     * @return array<string, string>
     */
    protected function getStatusChoices(): array
    {
        $statusChoices = [];
        foreach ($this->merchantRelationRequestPageConfig->getPossibleStatuses() as $status) {
            $statusChoices[$status] = sprintf('merchant_relation_request_page.merchant_relation_request.status.%s', $status);
        }

        return $statusChoices;
    }
}
