<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class AddressController extends AbstractCompanyController
{
    public const COMPANY_UNIT_ADDRESS_LIST_SORT_FIELD = 'id_company_unit_address';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $companyUnitAddressCollectionTransfer = $this->createCompanyUnitAddressCollectionTransfer($request);
        $companyUnitAddressCollectionTransfer = $this->getFactory()
            ->getCompanyUnitAddressClient()
            ->getCompanyUnitAddressCollection($companyUnitAddressCollectionTransfer);

        $data = [
            'pagination' => $companyUnitAddressCollectionTransfer->getPagination(),
            'addresses' => $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses(),
        ];

        return $this->view($data);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $dataProvider = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->createCompanyUnitAddressFormDataProvider();

        $addressForm = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->getCompanyUnitAddressForm($dataProvider->getOptions())
            ->handleRequest($request);

        if ($addressForm->isSubmitted() === false) {
            $companyUserTransfer = $this->getCompanyUser();
            $addressForm->setData($dataProvider->getData($companyUserTransfer));
        }

        if ($addressForm->isValid()) {
            $companyUnitAddressTransfer = $this->saveAddress($addressForm->getData());

            if ($companyUnitAddressTransfer) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_ADDRESS);
            }
        }

        return $this->view([
            'form' => $addressForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $dataProvider = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->createCompanyUnitAddressFormDataProvider();

        $addressForm = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->getCompanyUnitAddressForm($dataProvider->getOptions())
            ->handleRequest($request);

        if ($addressForm->isSubmitted() === false) {
            $idCompanyUnitAddress = $request->query->getInt('id');
            $addressForm->setData($dataProvider->getData($this->getCompanyUser(), $idCompanyUnitAddress));
        } elseif ($addressForm->isValid()) {
            $this->saveAddress($addressForm->getData());

            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_ADDRESS);
        }

        return $this->view([
            'form' => $addressForm->createView(),
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idCompanyUnitAddress = $request->query->getInt('id');
        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $this->getFactory()->getCompanyUnitAddressClient()->deleteCompanyUnitAddress($companyUnitAddressTransfer);

        return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_ADDRESS);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    protected function saveAddress(array $data)
    {
        $addressTransfer = new CompanyUnitAddressTransfer();
        $addressTransfer->fromArray($data, true);
        $addressTransfer = $this
            ->getFactory()
            ->getCompanyUnitAddressClient()
            ->createCompanyUnitAddress($addressTransfer);

        return $addressTransfer->getCompanyUnitAddressTransfer();
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

        $filterTransfer = $this->createFilterTransfer(static::COMPANY_UNIT_ADDRESS_LIST_SORT_FIELD);
        $companyUnitAddressCollectionTransfer->setFilter($filterTransfer);

        $paginationTransfer = $this->createPaginationTransfer($request);
        $companyUnitAddressCollectionTransfer->setPagination($paginationTransfer);

        return $companyUnitAddressCollectionTransfer;
    }
}
