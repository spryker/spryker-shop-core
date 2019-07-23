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
use SprykerShop\Yves\CompanyPage\Form\CompanyUnitAddressForm;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    protected const REFERER_PARAM = 'referer';

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
            $addressForm->setData($dataProvider->getData($this->findCurrentCompanyUserTransfer()));
        }

        if ($addressForm->isSubmitted() === true && $addressForm->isValid() === true) {
            $data = $addressForm->getData();
            $data[CompanyUnitAddressTransfer::COMPANY_BUSINESS_UNITS][CompanyBusinessUnitCollectionTransfer::COMPANY_BUSINESS_UNITS][][CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT] = $idCompanyBusinessUnit;

            $companyUnitAddressTransfer = $this->getFactory()
                ->createCompanyBusinessAddressSaver()
                ->saveAddress($data);

            $this->addTranslatedSuccessMessage(static::MESSAGE_BUSINESS_UNIT_ADDRESS_CREATE_SUCCESS, [
                '%address%' => $companyUnitAddressTransfer->getAddress1(),
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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
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
            $data = $dataProvider->getData($this->findCurrentCompanyUserTransfer(), $idCompanyUnitAddress);

            if (!$this->isCurrentCustomerRelatedToCompany($data[CompanyUnitAddressForm::FIELD_FK_COMPANY])) {
                throw new NotFoundHttpException();
            }

            $addressForm->setData($data);
        }

        if ($addressForm->isSubmitted() === true && $addressForm->isValid() === true) {
            $data = $addressForm->getData();
            $data[CompanyUnitAddressTransfer::COMPANY_BUSINESS_UNITS][CompanyBusinessUnitCollectionTransfer::COMPANY_BUSINESS_UNITS][][CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT] = $idCompanyBusinessUnit;

            $companyUnitAddressTransfer = $this->getFactory()
                ->createCompanyBusinessAddressSaver()
                ->saveAddress($data);

            $this->addTranslatedSuccessMessage(static::MESSAGE_BUSINESS_UNIT_ADDRESS_UPDATE_SUCCESS, [
                '%address%' => $companyUnitAddressTransfer->getAddress1(),
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
            'idCompanyUnitAddress' => $idCompanyUnitAddress,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $idCompanyUnitAddress = $request->query->getInt(static::REQUEST_PARAM_ID);
        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID_COMPANY_BUSINESS_UNIT);
        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();
        $companyUnitAddressTransfer->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $companyUnitAddressTransfer = $this->getFactory()
            ->getCompanyUnitAddressClient()
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);

        if (!$this->isCurrentCustomerRelatedToCompany($companyUnitAddressTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmDeleteAction(Request $request)
    {
        $idCompanyUnitAddress = $request->query->getInt(static::REQUEST_PARAM_ID);
        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID_COMPANY_BUSINESS_UNIT);

        $referer = $request->headers->get(static::REFERER_PARAM);

        $companyUnitAddressTransfer = (new CompanyUnitAddressTransfer())
            ->setIdCompanyUnitAddress($idCompanyUnitAddress);

        $companyUnitAddressTransfer = $this->getFactory()
            ->getCompanyUnitAddressClient()
            ->getCompanyUnitAddressById($companyUnitAddressTransfer);

        if (!$this->isCurrentCustomerRelatedToCompany($companyUnitAddressTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

        return $this->view([
            'companyUnitAddress' => $companyUnitAddressTransfer,
            'idCompanyBusinessUnit' => $idCompanyBusinessUnit,
            'cancelUrl' => $referer,
        ], [], '@CompanyPage/views/address-delete-confirmation-page/address-delete-confirmation-page.twig');
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
        $criteriaFilterTransfer->setIdCompany($this->findCurrentCompanyUserTransfer()->getFkCompany());

        $filterTransfer = $this->createFilterTransfer(static::COMPANY_UNIT_ADDRESS_LIST_SORT_FIELD);
        $criteriaFilterTransfer->setFilter($filterTransfer);

        $paginationTransfer = $this->createPaginationTransfer($request);
        $criteriaFilterTransfer->setPagination($paginationTransfer);

        return $criteriaFilterTransfer;
    }
}
