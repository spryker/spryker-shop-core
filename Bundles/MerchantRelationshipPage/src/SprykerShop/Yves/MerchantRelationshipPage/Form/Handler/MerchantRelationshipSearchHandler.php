<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRelationshipPage\Form\Handler;

use Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer;
use Generated\Shared\Transfer\MerchantRelationshipConditionsTransfer;
use Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer;
use Generated\Shared\Transfer\MerchantRelationshipTransfer;
use Generated\Shared\Transfer\PaginationTransfer;
use Generated\Shared\Transfer\SortCollectionTransfer;
use Generated\Shared\Transfer\SortTransfer;
use SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantRelationshipClientInterface;
use SprykerShop\Yves\MerchantRelationshipPage\Form\MerchantRelationshipSearchFiltersSubForm;
use SprykerShop\Yves\MerchantRelationshipPage\Form\MerchantRelationshipSearchForm;
use SprykerShop\Yves\MerchantRelationshipPage\MerchantRelationshipPageConfig;
use SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyUserReaderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

class MerchantRelationshipSearchHandler implements MerchantRelationshipSearchHandlerInterface
{
    /**
     * @var \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantRelationshipClientInterface
     */
    protected MerchantRelationshipPageToMerchantRelationshipClientInterface $merchantRelationshipClient;

    /**
     * @var \SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyUserReaderInterface
     */
    protected CompanyUserReaderInterface $companyUserReader;

    /**
     * @param \SprykerShop\Yves\MerchantRelationshipPage\Dependency\Client\MerchantRelationshipPageToMerchantRelationshipClientInterface $merchantRelationshipClient
     * @param \SprykerShop\Yves\MerchantRelationshipPage\Reader\CompanyUserReaderInterface $companyUserReader
     */
    public function __construct(
        MerchantRelationshipPageToMerchantRelationshipClientInterface $merchantRelationshipClient,
        CompanyUserReaderInterface $companyUserReader
    ) {
        $this->merchantRelationshipClient = $merchantRelationshipClient;
        $this->companyUserReader = $companyUserReader;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Form\FormInterface $merchantRelationshipSearchForm
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCollectionTransfer
     */
    public function handleSearchFormSubmit(
        Request $request,
        FormInterface $merchantRelationshipSearchForm
    ): MerchantRelationshipCollectionTransfer {
        $merchantRelationshipCriteriaTransfer = $this->getMerchantRelationshipCriteriaTransfer($request);

        /** @var array<string, mixed> $data */
        $data = $request->query->all()[MerchantRelationshipSearchForm::FORM_NAME] ?? [];
        $isReset = $data[MerchantRelationshipSearchForm::FIELD_RESET] ?? null;

        if ($isReset) {
            return $this->merchantRelationshipClient->getMerchantRelationshipCollection($merchantRelationshipCriteriaTransfer);
        }

        $merchantRelationshipSearchForm->handleRequest($request);
        if (!$merchantRelationshipSearchForm->isSubmitted() || !$merchantRelationshipSearchForm->isValid()) {
            return $this->merchantRelationshipClient->getMerchantRelationshipCollection($merchantRelationshipCriteriaTransfer);
        }

        $merchantRelationshipCriteriaTransfer = $this->applyFormFilters(
            $merchantRelationshipSearchForm->getData(),
            $merchantRelationshipCriteriaTransfer,
        );

        return $this->merchantRelationshipClient->getMerchantRelationshipCollection($merchantRelationshipCriteriaTransfer);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    protected function getMerchantRelationshipCriteriaTransfer(Request $request): MerchantRelationshipCriteriaTransfer
    {
        $merchantRelationshipConditionsTransfer = (new MerchantRelationshipConditionsTransfer())
            ->addIdCompany($this->companyUserReader->getCurrentCompanyUser()->getFkCompanyOrFail())
            ->setIsActiveMerchant(true);

        $sortTransfer = (new SortTransfer())
            ->setField(MerchantRelationshipTransfer::CREATED_AT)
            ->setIsAscending(false);

        return (new MerchantRelationshipCriteriaTransfer())
            ->setMerchantRelationshipConditions($merchantRelationshipConditionsTransfer)
            ->setPagination($this->getPaginationTransfer($request))
            ->setSortCollection((new SortCollectionTransfer())->addSort($sortTransfer));
    }

    /**
     * @param array<string, mixed> $merchantRelationshipSearchFormData
     * @param \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
     *
     * @return \Generated\Shared\Transfer\MerchantRelationshipCriteriaTransfer
     */
    protected function applyFormFilters(
        array $merchantRelationshipSearchFormData,
        MerchantRelationshipCriteriaTransfer $merchantRelationshipCriteriaTransfer
    ): MerchantRelationshipCriteriaTransfer {
        $filterData = $merchantRelationshipSearchFormData[MerchantRelationshipSearchForm::FIELD_FILTERS];

        $idMerchant = $filterData[MerchantRelationshipSearchFiltersSubForm::FIELD_MERCHANT] ?? null;
        $idOwnerCompanyBusinessUnit = $filterData[MerchantRelationshipSearchFiltersSubForm::FIELD_OWNER_BUSINESS_UNIT] ?? null;

        if ($idMerchant) {
            $merchantRelationshipCriteriaTransfer
                ->getMerchantRelationshipConditionsOrFail()
                ->addIdMerchant($idMerchant);
        }

        if ($idOwnerCompanyBusinessUnit) {
            $merchantRelationshipCriteriaTransfer
                ->getMerchantRelationshipConditionsOrFail()
                ->addIdOwnerCompanyBusinessUnit($idOwnerCompanyBusinessUnit);
        }

        return $merchantRelationshipCriteriaTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\PaginationTransfer
     */
    protected function getPaginationTransfer(Request $request): PaginationTransfer
    {
        return (new PaginationTransfer())
            ->setPage($request->query->getInt(MerchantRelationshipPageConfig::PARAM_PAGE, MerchantRelationshipPageConfig::DEFAULT_PAGE))
            ->setMaxPerPage(MerchantRelationshipPageConfig::DEFAULT_MAX_PER_PAGE);
    }
}
