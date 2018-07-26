<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class AddressController extends AbstractCompanyController
{
    protected const COMPANY_UNIT_ADDRESS_LIST_SORT_FIELD = 'id_company_unit_address';
    protected const REQUEST_PARAM_ID_COMPANY_BUSINESS_UNIT = 'idCompanyBusinessUnit';
    protected const REQUEST_PARAM_ID = 'id';

    protected const MESSAGE_BUSINESS_UNIT_ADDRESS_CREATE_SUCCESS = 'message.business_unit_address.create';
    protected const MESSAGE_BUSINESS_UNIT_ADDRESS_UPDATE_SUCCESS = 'message.business_unit_address.update';
    protected const MESSAGE_BUSINESS_UNIT_ADDRESS_DELETE_SUCCESS = 'message.business_unit_address.delete';

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

        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID_COMPANY_BUSINESS_UNIT);

        if ($addressForm->isSubmitted() === false) {
            $addressForm->setData($dataProvider->getData($this->getCompanyUser()));
        }

        if ($addressForm->isValid()) {
            $companyUnitAddressTransfer = $this->saveAddress($addressForm->getData(), $idCompanyBusinessUnit);

            $this->addTranslatedSuccessMessage(static::MESSAGE_BUSINESS_UNIT_ADDRESS_CREATE_SUCCESS, [
                '%address%' => $companyUnitAddressTransfer->getAddress1()
            ]);

            if (empty($idCompanyBusinessUnit)) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT);
            }

            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE, [
                'id' => $idCompanyBusinessUnit,
            ]);
        }

        return [
            'form' => $addressForm->createView(),
            'idCompanyBusinessUnit' => $idCompanyBusinessUnit,
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

        $idCompanyUnitAddress = $request->query->getInt(static::REQUEST_PARAM_ID);
        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID_COMPANY_BUSINESS_UNIT);

        if ($addressForm->isSubmitted() === false) {
            $addressForm->setData($dataProvider->getData($this->getCompanyUser(), $idCompanyUnitAddress));
        }

        if ($addressForm->isValid()) {
            $companyUnitAddressTransfer = $this->saveAddress($addressForm->getData(), null);

            $this->addTranslatedSuccessMessage(static::MESSAGE_BUSINESS_UNIT_ADDRESS_UPDATE_SUCCESS, [
                '%address%' => $companyUnitAddressTransfer->getAddress1()
            ]);

            if (empty($idCompanyBusinessUnit)) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT);
            }

            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE, [
                'id' => $idCompanyBusinessUnit,
            ]);
        }

        return [
            'form' => $addressForm->createView(),
            'idCompanyBusinessUnit' => $idCompanyBusinessUnit,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idCompanyUnitAddress = $request->query->getInt(static::REQUEST_PARAM_ID);
        $idCompanyBusinessUnit = $request->query->get(static::REQUEST_PARAM_ID_COMPANY_BUSINESS_UNIT);
        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $this->getFactory()->getCompanyUnitAddressClient()->deleteCompanyUnitAddress($companyUnitAddressTransfer);

        $this->addTranslatedSuccessMessage(static::MESSAGE_BUSINESS_UNIT_ADDRESS_DELETE_SUCCESS);

        if (empty($idCompanyBusinessUnit)) {
            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT);
        }

        return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE, [
            'id' => $idCompanyBusinessUnit,
        ]);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmDeleteAction(Request $request)
    {
        $idCompanyUnitAddress = $request->query->getInt(static::REQUEST_PARAM_ID);
        $idCompanyBusinessUnit = $request->query->get(static::REQUEST_PARAM_ID_COMPANY_BUSINESS_UNIT);

        $companyUnitAddressTransfer = (new CompanyUnitAddressTransfer())
            ->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $companyUnitAddressTransfer = $this->getFactory()
            ->getCompanyUnitAddressClient()
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);

        return $this->view([
            'companyUnitAddress' => $companyUnitAddressTransfer,
            'idCompanyBusinessUnit' => $idCompanyBusinessUnit,
        ], [], '@CompanyPage/views/address-delete-confirmation-page/address-delete-confirmation-page.twig');
    }

    /**
     * @param array $data
     * @param int|null $idCompanyBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    protected function saveAddress(array $data, ?int $idCompanyBusinessUnit = null)
    {
        $addressTransfer = new CompanyUnitAddressTransfer();
        $addressTransfer->fromArray($data, true);

        if ($idCompanyBusinessUnit) {
            $companyBusinessUnitCollectionTransfer = $this->createCompanyBusinessCollectionUnitTransfer($idCompanyBusinessUnit);
            $addressTransfer
                ->setCompanyBusinessUnitCollection($companyBusinessUnitCollectionTransfer);
        }

        $addressTransfer = $this
            ->getFactory()
            ->getCompanyUnitAddressClient()
            ->createCompanyUnitAddress($addressTransfer);

        return $addressTransfer->getCompanyUnitAddressTransfer();
    }

    /**
     * @param int|null $idBusinessUnit
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer
     */
    protected function createCompanyBusinessCollectionUnitTransfer(?int $idBusinessUnit = null): CompanyBusinessUnitCollectionTransfer
    {
        $companyBusinessUnitCollectionTransfer = new CompanyBusinessUnitCollectionTransfer();

        $companyBusinessUnitTransfer = (new CompanyBusinessUnitTransfer())
            ->setIdCompanyBusinessUnit($idBusinessUnit);

        $companyBusinessUnitCollectionTransfer->addCompanyBusinessUnit($companyBusinessUnitTransfer);

        return $companyBusinessUnitCollectionTransfer;
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
