<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Form\DataProvider;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\MerchantTransfer;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCustomerClientInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\MerchantRelationRequestForm;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyBusinessUnitReaderInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantSearchReaderInterface;

class MerchantRelationRequestFormDataProvider
{
    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCustomerClientInterface
     */
    protected MerchantRelationRequestPageToCustomerClientInterface $customerClient;

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
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToCustomerClientInterface $customerClient
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Reader\MerchantSearchReaderInterface $merchantSearchReader
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyBusinessUnitReaderInterface $companyBusinessUnitReader
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface $companyUserReader
     */
    public function __construct(
        MerchantRelationRequestPageToCustomerClientInterface $customerClient,
        MerchantSearchReaderInterface $merchantSearchReader,
        CompanyBusinessUnitReaderInterface $companyBusinessUnitReader,
        CompanyUserReaderInterface $companyUserReader
    ) {
        $this->customerClient = $customerClient;
        $this->merchantSearchReader = $merchantSearchReader;
        $this->companyBusinessUnitReader = $companyBusinessUnitReader;
        $this->companyUserReader = $companyUserReader;
    }

    /**
     * @param string $merchantReference
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestTransfer
     */
    public function getData(string $merchantReference): MerchantRelationRequestTransfer
    {
        $companyUserTransfer = $this->getCurrentCompanyUser();
        $merchantTransfer = (new MerchantTransfer())
            ->setMerchantReference($merchantReference !== '' ? $merchantReference : null);

        return (new MerchantRelationRequestTransfer())
            ->setMerchant($merchantTransfer)
            ->setCompanyUser($companyUserTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     *
     * @return array<string, mixed>
     */
    public function getOptions(MerchantRelationRequestTransfer $merchantRelationRequestTransfer): array
    {
        $idCompany = $merchantRelationRequestTransfer->getCompanyUserOrFail()->getFkCompanyOrFail();

        $merchantSearchTransfers = $this->merchantSearchReader->getMerchantSearchTransferIndexedByMerchantReference();
        $merchantSearchTransfers = $this->filterMerchants($merchantSearchTransfers);

        $companyBusinessUnitTransfers = $this->companyBusinessUnitReader
            ->getCompanyBusinessUnitsIndexedByIdCompanyBusinessUnit($idCompany);

        return [
            MerchantRelationRequestForm::OPTION_MERCHANTS => $merchantSearchTransfers,
            MerchantRelationRequestForm::OPTION_BUSINESS_UNITS => $companyBusinessUnitTransfers,
            MerchantRelationRequestForm::OPTION_MERCHANT_CHOICES => $this->getMerchantChoices($merchantSearchTransfers),
            MerchantRelationRequestForm::OPTION_BUSINESS_UNIT_CHOICES => $this->getCompanyBusinessUnitChoices($companyBusinessUnitTransfers),
            MerchantRelationRequestForm::OPTION_SELECTED_MERCHANT_REFERENCE => $this->findSelectedMerchantReference(
                $merchantRelationRequestTransfer,
                $merchantSearchTransfers,
            ),
        ];
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function getCurrentCompanyUser(): CompanyUserTransfer
    {
        $companyUserTransfer = $this->companyUserReader->getCurrentCompanyUser();
        $customerTransfer = $this->customerClient->getCustomer();

        if (!$customerTransfer) {
            return $companyUserTransfer;
        }

        return $companyUserTransfer->setCustomer((new CustomerTransfer())
            ->setFirstName($customerTransfer->getFirstName())
            ->setLastName($customerTransfer->getLastName())
            ->setEmail($customerTransfer->getEmail()));
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\MerchantSearchTransfer> $indexedMerchantSearchTransfers
     *
     * @return array<string, string>
     */
    protected function getMerchantChoices(array $indexedMerchantSearchTransfers): array
    {
        $merchants = [];
        foreach ($indexedMerchantSearchTransfers as $merchantSearchTransfer) {
            $merchants[$merchantSearchTransfer->getMerchantReferenceOrFail()] = $merchantSearchTransfer->getNameOrFail();
        }

        return $merchants;
    }

    /**
     * @param array<int, \Generated\Shared\Transfer\CompanyBusinessUnitTransfer> $companyBusinessUnitTransfers
     *
     * @return array<int, string>
     */
    protected function getCompanyBusinessUnitChoices(array $companyBusinessUnitTransfers): array
    {
        $businessUnitsChoices = [];
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
     * @param \Generated\Shared\Transfer\MerchantRelationRequestTransfer $merchantRelationRequestTransfer
     * @param array<string, \Generated\Shared\Transfer\MerchantSearchTransfer> $indexedMerchantSearchTransfers
     *
     * @return string|null
     */
    protected function findSelectedMerchantReference(
        MerchantRelationRequestTransfer $merchantRelationRequestTransfer,
        array $indexedMerchantSearchTransfers
    ): ?string {
        $selectedMerchantReference = $merchantRelationRequestTransfer->getMerchantOrFail()->getMerchantReference();

        if ($selectedMerchantReference) {
            return $selectedMerchantReference;
        }

        if (count($indexedMerchantSearchTransfers) === 1) {
            return array_key_first($indexedMerchantSearchTransfers);
        }

        return null;
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\MerchantSearchTransfer> $indexedMerchantSearchTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\MerchantSearchTransfer>
     */
    protected function filterMerchants(array $indexedMerchantSearchTransfers): array
    {
        $filteredMerchantSearchTransfers = [];
        foreach ($indexedMerchantSearchTransfers as $merchantReference => $merchantSearchTransfer) {
            if ($merchantSearchTransfer->getIsOpenForRelationRequest()) {
                $filteredMerchantSearchTransfers[$merchantReference] = $merchantSearchTransfer;
            }
        }

        return $filteredMerchantSearchTransfers;
    }
}
