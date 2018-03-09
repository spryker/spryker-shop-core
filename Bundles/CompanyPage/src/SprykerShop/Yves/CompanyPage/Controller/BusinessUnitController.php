<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class BusinessUnitController extends AbstractCompanyController
{
    public const BUSINESS_UNIT_LIST_SORT_FIELD = 'id_company_business_unit';
    public const COMPANY_UNIT_ADDRESS_LIST_SORT_FIELD = 'id_company_unit_address';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $businessUnitCollectionTransfer = $this->createBusinessUnitCriteriaFilterTransfer($request);
        $businessUnitCollectionTransfer = $this->getFactory()
            ->getCompanyBusinessUnitClient()
            ->getCompanyBusinessUnitCollection($businessUnitCollectionTransfer);

        $data = [
            'pagination' => $businessUnitCollectionTransfer->getPagination(),
            'businessUnitCollection' => $businessUnitCollectionTransfer->getCompanyBusinessUnits(),
        ];

        return $this->view($data, [], '@CompanyPage/views/business-unit/business-unit.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailsAction(Request $request)
    {
        $data = $this->getCompanyBusinessUnitDetailsResponseData($request);

        return $this->view($data, [], '@CompanyPage/views/business-unit-detail/business-unit-detail.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createCompanyFormFactory()
            ->createBusinessUnitFormDataProvider();

        $companyBusinessUnitForm = $this->getFactory()
            ->createCompanyFormFactory()
            ->getBusinessUnitForm()
            ->handleRequest($request);

        if ($companyBusinessUnitForm->isSubmitted() === false) {
            $companyBusinessUnitForm->setData($dataProvider->getData($this->getCompanyUser()));
        } elseif ($companyBusinessUnitForm->isValid()) {
            $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitUpdate($companyBusinessUnitForm->getData());

            if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT);
            }
        }

        $data = [
            'form' => $companyBusinessUnitForm->createView(),
        ];

        return $this->view($data, [], '@CompanyPage/views/business-unit-create/business-unit-create.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createCompanyFormFactory()
            ->createBusinessUnitFormDataProvider();

        $companyBusinessUnitForm = $this->getFactory()
            ->createCompanyFormFactory()
            ->getBusinessUnitForm()
            ->handleRequest($request);

        if ($companyBusinessUnitForm->isSubmitted() === false) {
            $companyBusinessUnitId = $request->query->getInt('id');
            $companyBusinessUnitForm->setData(
                $dataProvider->getData(
                    $this->getCompanyUser(),
                    $companyBusinessUnitId
                )
            );
        } elseif ($companyBusinessUnitForm->isValid()) {
            $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitUpdate($companyBusinessUnitForm->getData());

            if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT);
            }
        }

        $data = [
            'form' => $companyBusinessUnitForm->createView(),
        ];

        return $this->view($data, [], '@CompanyPage/views/business-unit-update/business-unit-update.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $companyBusinessUnitId = $request->query->getInt('id');
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($companyBusinessUnitId);

        $this->getFactory()->getCompanyBusinessUnitClient()->deleteCompanyBusinessUnit($companyBusinessUnitTransfer);

        return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer
     */
    protected function createBusinessUnitCriteriaFilterTransfer(Request $request): CompanyBusinessUnitCriteriaFilterTransfer
    {
        $criteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();

        $criteriaFilterTransfer->setIdCompany($this->getCompanyUser()->getFkCompany());

        $filterTransfer = $this->createFilterTransfer(self::BUSINESS_UNIT_LIST_SORT_FIELD);
        $criteriaFilterTransfer->setFilter($filterTransfer);

        $paginationTransfer = $this->createPaginationTransfer($request);
        $criteriaFilterTransfer->setPagination($paginationTransfer);

        return $criteriaFilterTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function getCompanyBusinessUnitDetailsResponseData(Request $request): array
    {
        $idCompanyBusinessUnit = $request->query->getInt('id');
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);

        $companyBusinessUnitTransfer = $this->getFactory()->getCompanyBusinessUnitClient()
            ->getCompanyBusinessUnitById($companyBusinessUnitTransfer);

        $criteriaFilterTransfer = $this->createCompanyUnitAddressCriteriaFilterTransfer($request);
        $criteriaFilterTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);
        $criteriaFilterTransfer = $this->getFactory()
            ->getCompanyUnitAddressClient()
            ->getCompanyUnitAddressCollection($criteriaFilterTransfer);
        $addresses = $criteriaFilterTransfer->getCompanyUnitAddresses();

        foreach ($addresses as &$address) {
            if ($address->getIdCompanyUnitAddress() === $companyBusinessUnitTransfer->getDefaultBillingAddress()) {
                $address->setIsDefaultBilling(true);
            }
        }

        return [
            'addresses' => $addresses,
            'pagination' => $criteriaFilterTransfer->getPagination(),
            'businessUnit' => $companyBusinessUnitTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer
     */
    protected function createCompanyUnitAddressCriteriaFilterTransfer(
        Request $request
    ): CompanyUnitAddressCriteriaFilterTransfer {
        $criteriaFilterTransfer = new CompanyUnitAddressCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompany($this->getCompanyUser()->getFkCompany());

        $filterTransfer = $this->createFilterTransfer(self::COMPANY_UNIT_ADDRESS_LIST_SORT_FIELD);
        $criteriaFilterTransfer->setFilter($filterTransfer);

        $paginationTransfer = $this->createPaginationTransfer($request);
        $criteriaFilterTransfer->setPagination($paginationTransfer);

        return $criteriaFilterTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    protected function companyBusinessUnitUpdate(array $data): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->fromArray($data, true);

        return $this->getFactory()
            ->getCompanyBusinessUnitClient()
            ->updateCompanyBusinessUnit($companyBusinessUnitTransfer);
    }
}
