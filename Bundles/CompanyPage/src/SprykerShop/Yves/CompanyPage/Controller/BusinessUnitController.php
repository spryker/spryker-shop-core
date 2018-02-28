<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
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
        $businessUnitCollectionTransfer = $this->createBusinessUnitCollectionTransfer($request);
        $businessUnitCollectionTransfer = $this->getFactory()->getCompanyBusinessUnitClient()->getCompanyBusinessUnitCollection($businessUnitCollectionTransfer);

        $data = [
            'pagination' => $businessUnitCollectionTransfer->getPagination(),
            'businessUnitCollection' => $businessUnitCollectionTransfer->getCompanyBusinessUnits(),
        ];

        return $this->view($data);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailsAction(Request $request)
    {
        $responseData = $this->getCompanyBusinessUnitDetailsResponseData($request);

        return $this->view($responseData);
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

        return $this->view([
            'form' => $companyBusinessUnitForm->createView(),
        ]);
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
            $companyBusinessUnitForm->setData($dataProvider->getData($this->getCompanyUser(), $companyBusinessUnitId));
        } elseif ($companyBusinessUnitForm->isValid()) {
            $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitUpdate($companyBusinessUnitForm->getData());

            if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT);
            }
        }

        return $this->view([
            'form' => $companyBusinessUnitForm->createView(),
        ]);
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
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function createBusinessUnitCollectionTransfer(Request $request): CompanyBusinessUnitCollectionTransfer
    {
        $businessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();
        $companyUserTransfer = $this->getCompanyUser();

        $businessUnitCollectionTransfer->setIdCompany($companyUserTransfer->getFkCompany());
        $businessUnitCollectionTransfer->setIdCompanyUser($companyUserTransfer->getIdCompanyUser());

        $filterTransfer = $this->createFilterTransfer(self::BUSINESS_UNIT_LIST_SORT_FIELD);
        $businessUnitCollectionTransfer->setFilter($filterTransfer);

        $paginationTransfer = $this->createPaginationTransfer($request);
        $businessUnitCollectionTransfer->setPagination($paginationTransfer);

        return $businessUnitCollectionTransfer;
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

        $responseTransfer = $this->getFactory()->getCompanyBusinessUnitClient()
            ->getCompanyBusinessUnitById($companyBusinessUnitTransfer);
        $companyBusinessUnitTransfer = $responseTransfer->getCompanyBusinessUnitTransfer();

        $companyUnitAddressCollectionTransfer = $this->createCompanyUnitAddressCollectionTransfer($request);
        $companyUnitAddressCollectionTransfer->setFkCompanyBusinessUnit($idCompanyBusinessUnit);
        $companyUnitAddressCollectionTransfer = $this->getFactory()
            ->getCompanyUnitAddressClient()
            ->getCompanyUnitAddressCollection($companyUnitAddressCollectionTransfer);
        $addresses = $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses();

        foreach ($addresses as &$address) {
            if ($address->getIdCompanyUnitAddress() === $companyBusinessUnitTransfer->getDefaultBillingAddress()) {
                $address->setIsDefaultBilling(true);
            }
        }

        return [
            'addresses' => $addresses,
            'pagination' => $companyUnitAddressCollectionTransfer->getPagination(),
            'businessUnit' => $companyBusinessUnitTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer
     */
    protected function createCompanyUnitAddressCollectionTransfer(
        Request $request
    ): CompanyUnitAddressCollectionTransfer {
        $companyUnitAddressCollectionTransfer = new CompanyUnitAddressCollectionTransfer();
        $companyUnitAddressCollectionTransfer->setFkCompany($this->getCompanyUser()->getFkCompany());

        $filterTransfer = $this->createFilterTransfer(self::COMPANY_UNIT_ADDRESS_LIST_SORT_FIELD);
        $companyUnitAddressCollectionTransfer->setFilter($filterTransfer);

        $paginationTransfer = $this->createPaginationTransfer($request);
        $companyUnitAddressCollectionTransfer->setPagination($paginationTransfer);

        return $companyUnitAddressCollectionTransfer;
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
