<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationRequestPage\Form\Handler;

use Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationRequestTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantRelationRequestClientInterface;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\MerchantRelationRequestSearchFiltersSubForm;
use SprykerShop\Yves\MerchantRelationRequestPage\Form\MerchantRelationRequestSearchForm;
use SprykerShop\Yves\MerchantRelationRequestPage\MerchantRelationRequestPageConfig;
use SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class MerchantRelationRequestSearchHandler implements MerchantRelationRequestSearchHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantRelationRequestClientInterface
     */
    protected MerchantRelationRequestPageToMerchantRelationRequestClientInterface $merchantRelationRequestClient;

    /**
     * @var \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface
     */
    protected CompanyUserReaderInterface $companyUserReader;

    /**
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Dependency\Client\MerchantRelationRequestPageToMerchantRelationRequestClientInterface $merchantRelationRequestClient
     * @param \SprykerShop\Yves\MerchantRelationRequestPage\Reader\CompanyUserReaderInterface $companyUserReader
     */
    public function __construct(
        MerchantRelationRequestPageToMerchantRelationRequestClientInterface $merchantRelationRequestClient,
        CompanyUserReaderInterface $companyUserReader
    ) {
        $this->merchantRelationRequestClient = $merchantRelationRequestClient;
        $this->companyUserReader = $companyUserReader;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $merchantRelationRequestSearchForm
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCollectionTransfer
     */
    public function handleSearchFormSubmit(
        Request $request,
        FormInterface $merchantRelationRequestSearchForm
    ): MerchantRelationRequestCollectionTransfer {
        $merchantRelationRequestCriteriaTransfer = $this->getMerchantRelationRequestCriteriaTransfer($request);

        /** @var array<string, mixed> $data */
        $data = $request->query->all()[MerchantRelationRequestSearchForm::FORM_NAME] ?? [];
        $isReset = $data[MerchantRelationRequestSearchForm::FIELD_RESET] ?? null;

        if ($isReset) {
            return $this->merchantRelationRequestClient->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);
        }

        $merchantRelationRequestSearchForm->handleRequest($request);
        if (!$merchantRelationRequestSearchForm->isSubmitted() || !$merchantRelationRequestSearchForm->isValid()) {
            return $this->merchantRelationRequestClient->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);
        }

        $merchantRelationRequestCriteriaTransfer = $this->applyFormFilters(
            $merchantRelationRequestSearchForm->getData(),
            $merchantRelationRequestCriteriaTransfer,
        );

        return $this->merchantRelationRequestClient->getMerchantRelationRequestCollection($merchantRelationRequestCriteriaTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer
     */
    protected function getMerchantRelationRequestCriteriaTransfer(Request $request): MerchantRelationRequestCriteriaTransfer
    {
        $merchantRelationRequestConditionsTransfer = (new MerchantRelationRequestConditionsTransfer())
            ->addIdCompany($this->companyUserReader->getCurrentCompanyUser()->getFkCompanyOrFail())
            ->setWithAssigneeCompanyBusinessUnitRelations(true);

        $sortTransfer = (new SortTransfer())
            ->setField(MerchantRelationRequestTransfer::CREATED_AT)
            ->setIsAscending(false);

        return (new MerchantRelationRequestCriteriaTransfer())
            ->setMerchantRelationRequestConditions($merchantRelationRequestConditionsTransfer)
            ->setPagination($this->getPaginationTransfer($request))
            ->addSort($sortTransfer);
    }

    /**
     * @param array<string, mixed> $merchantRelationRequestSearchFormData
     * @param \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationRequestCriteriaTransfer
     */
    protected function applyFormFilters(
        array $merchantRelationRequestSearchFormData,
        MerchantRelationRequestCriteriaTransfer $merchantRelationRequestCriteriaTransfer
    ): MerchantRelationRequestCriteriaTransfer {
        $filterData = $merchantRelationRequestSearchFormData[MerchantRelationRequestSearchForm::FIELD_FILTERS];

        $idMerchant = $filterData[MerchantRelationRequestSearchFiltersSubForm::FIELD_MERCHANT] ?? null;
        $idOwnerCompanyBusinessUnit = $filterData[MerchantRelationRequestSearchFiltersSubForm::FIELD_OWNER_BUSINESS_UNIT] ?? null;
        $status = $filterData[MerchantRelationRequestSearchFiltersSubForm::FIELD_STATUS] ?? null;

        if ($idMerchant) {
            $merchantRelationRequestCriteriaTransfer
                ->getMerchantRelationRequestConditionsOrFail()
                ->addIdMerchant($idMerchant);
        }

        if ($idOwnerCompanyBusinessUnit) {
            $merchantRelationRequestCriteriaTransfer
                ->getMerchantRelationRequestConditionsOrFail()
                ->addIdOwnerCompanyBusinessUnit($idOwnerCompanyBusinessUnit);
        }

        if ($status) {
            $merchantRelationRequestCriteriaTransfer
                ->getMerchantRelationRequestConditionsOrFail()
                ->addStatus($status);
        }

        return $merchantRelationRequestCriteriaTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function getPaginationTransfer(Request $request): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($request->query->getInt(MerchantRelationRequestPageConfig::PARAM_PAGE, MerchantRelationRequestPageConfig::DEFAULT_PAGE))
            ->setMaxPerPage(MerchantRelationRequestPageConfig::DEFAULT_MAX_PER_PAGE);
    }
}
