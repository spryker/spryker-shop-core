<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $viewData = $this->executeIndexAction($request);

        return $this->view($viewData, [], '@CompanyPage/views/address/address.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeIndexAction(Request $request): array
    {
        $companyUnitAddressCollectionTransfer = $this->createCriteriaFilterTransfer($request);
        $companyUnitAddressCollectionTransfer = $this->getFactory()
            ->getCompanyUnitAddressClient()
            ->getCompanyUnitAddressCollection($companyUnitAddressCollectionTransfer);

        return [
            'pagination' => $companyUnitAddressCollectionTransfer->getPagination(),
            'addresses' => $companyUnitAddressCollectionTransfer->getCompanyUnitAddresses(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $response = $this->executeCreateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CompanyPage/views/address-create/address-create.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateAction(Request $request)
    {
        $dataProvider = $this
            ->getFactory()
            ->createCompanyPageFormFactory()
            ->createCompanyUnitAddressFormDataProvider();

        $addressForm = $this
            ->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyUnitAddressForm($dataProvider->getOptions())
            ->handleRequest($request);

        if ($addressForm->isSubmitted() === false) {
            $addressForm->setData($dataProvider->getData($this->getCompanyUser()));
        }

        if ($addressForm->isValid()) {
            $companyUnitAddressTransfer = $this->saveAddress($addressForm->getData());

            if ($companyUnitAddressTransfer) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_ADDRESS);
            }
        }

        return [
            'form' => $addressForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function updateAction(Request $request)
    {
        $response = $this->executeUpdateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CompanyPage/views/address-update/address-update.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeUpdateAction(Request $request)
    {
        $dataProvider = $this
            ->getFactory()
            ->createCompanyPageFormFactory()
            ->createCompanyUnitAddressFormDataProvider();

        $addressForm = $this
            ->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyUnitAddressForm($dataProvider->getOptions())
            ->handleRequest($request);

        if ($addressForm->isSubmitted() === false) {
            $idCompanyUnitAddress = $request->query->getInt('id');
            $addressForm->setData($dataProvider->getData($this->getCompanyUser(), $idCompanyUnitAddress));
        } elseif ($addressForm->isValid()) {
            $this->saveAddress($addressForm->getData());

            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_ADDRESS);
        }

        return [
            'form' => $addressForm->createView(),
        ];
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
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmDeleteAction(Request $request)
    {
        $idCompanyUnitAddress = $request->query->getInt('id');
        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $companyUnitAddressTransfer = $this->getFactory()->getCompanyUnitAddressClient()->getCompanyUnitAddressById($companyUnitAddressTransfer);

        return $this->view([
            'companyUnitAddress' => $companyUnitAddressTransfer,
        ], [], '@CompanyPage/views/address-delete-confirmation-page/address-delete-confirmation-page.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function cancelConfirmDeleteAction(Request $request): RedirectResponse
    {
        $idCompanyBusinessUnit = $request->query->getInt('id');
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
     * @return \Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer
     */
    protected function createCriteriaFilterTransfer(
        Request $request
    ): CompanyUnitAddressCriteriaFilterTransfer {
        $criteriaFilterTransfer = new CompanyUnitAddressCriteriaFilterTransfer();
        $criteriaFilterTransfer->setIdCompany($this->getCompanyUser()->getFkCompany());

        $filterTransfer = $this->createFilterTransfer(static::COMPANY_UNIT_ADDRESS_LIST_SORT_FIELD);
        $criteriaFilterTransfer->setFilter($filterTransfer);

        $paginationTransfer = $this->createPaginationTransfer($request);
        $criteriaFilterTransfer->setPagination($paginationTransfer);

        return $criteriaFilterTransfer;
    }
}
