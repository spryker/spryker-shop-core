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
use SprykerShop\Yves\CompanyPage\Form\CompanyBusinessUnitForm;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class BusinessUnitController extends AbstractCompanyController
{
    protected const BUSINESS_UNIT_LIST_SORT_FIELD = 'id_company_business_unit';
    protected const COMPANY_UNIT_ADDRESS_LIST_SORT_FIELD = 'id_company_unit_address';
    protected const REQUEST_PARAM_ID = 'id';

    protected const MESSAGE_BUSINESS_UNIT_CREATE_SUCCESS = 'Business unit "%s" was created successfully.';
    protected const MESSAGE_BUSINESS_UNIT_UPDATE_SUCCESS = 'Business unit "%s" was updated successfully.';
    protected const MESSAGE_BUSINESS_UNIT_DELETE_SUCCESS = 'Business unit "%s" was deleted successfully.';

    /**
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction()
    {
        $viewData = $this->executeIndexAction();

        return $this->view($viewData, [], '@CompanyPage/views/business-unit/business-unit.twig');
    }

    /**
     * @return array
     */
    protected function executeIndexAction(): array
    {
        return [
            'businessUnitsTree' => $this->getFactory()->createCompanyBusinessUnitTreeBuilder()->getCompanyBusinessUnitTree(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function detailsAction(Request $request)
    {
        $viewData = $this->getCompanyBusinessUnitDetailsResponseData($request);

        return $this->view($viewData, [], '@CompanyPage/views/business-unit-detail/business-unit-detail.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $response = $this->executeCreateAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CompanyPage/views/business-unit-create/business-unit-create.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeCreateAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->createBusinessUnitFormDataProvider();

        $companyUserTransfer = $this->getCompanyUser();
        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID);
        $dataProviderOptions = $dataProvider->getOptions($companyUserTransfer, $idCompanyBusinessUnit);

        $companyBusinessUnitForm = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getBusinessUnitForm($dataProviderOptions)
            ->handleRequest($request);

        if ($companyBusinessUnitForm->isSubmitted() === false) {
            $companyBusinessUnitForm->setData($dataProvider->getData($this->getCompanyUser()));
        } elseif ($companyBusinessUnitForm->isValid()) {
            $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitUpdate($companyBusinessUnitForm->getData());

            if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
                $this->getFactory()->getMessengerClient()->addSuccessMessage(
                    sprintf(
                        static::MESSAGE_BUSINESS_UNIT_CREATE_SUCCESS,
                        $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getName()
                    )
                );
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE, [
                    'id' => $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getIdCompanyBusinessUnit(),
                ]);
            }
        }

        return [
            'form' => $companyBusinessUnitForm->createView(),
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

        return $this->view($response, [], '@CompanyPage/views/business-unit-update/business-unit-update.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeUpdateAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->createBusinessUnitFormDataProvider();

        $companyUserTransfer = $this->getCompanyUser();
        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID);

        $dataProviderOptions = $dataProvider->getOptions($companyUserTransfer, $idCompanyBusinessUnit);
        $companyBusinessUnitForm = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getBusinessUnitForm($dataProviderOptions)
            ->handleRequest($request);

        if ($companyBusinessUnitForm->isSubmitted() === false) {
            $companyBusinessUnitForm->setData(
                $dataProvider->getData(
                    $this->getCompanyUser(),
                    $idCompanyBusinessUnit
                )
            );
        } elseif ($companyBusinessUnitForm->isValid()) {
            $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitUpdate($companyBusinessUnitForm->getData());

            if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
                $this->getFactory()->getMessengerClient()->addSuccessMessage(
                    sprintf(
                        static::MESSAGE_BUSINESS_UNIT_UPDATE_SUCCESS,
                        $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getName()
                    )
                );

                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE, [
                    'id' => $idCompanyBusinessUnit,
                ]);
            }
        }

        return [
            'form' => $companyBusinessUnitForm->createView(),
            'addresses' => $dataProviderOptions[CompanyBusinessUnitForm::FIELD_COMPANY_UNIT_ADDRESSES],
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request)
    {
        $companyBusinessUnitId = $request->query->getInt(static::REQUEST_PARAM_ID);
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($companyBusinessUnitId);

        $companyBusinessUnitResponseTransfer = $this->getFactory()
            ->getCompanyBusinessUnitClient()
            ->deleteCompanyBusinessUnit($companyBusinessUnitTransfer);

        if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
            $this->getFactory()->getMessengerClient()->addSuccessMessage(
                sprintf(
                    static::MESSAGE_BUSINESS_UNIT_DELETE_SUCCESS,
                    $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getName()
                )
            );
        }

        $this->processResponseMessages($companyBusinessUnitResponseTransfer);

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
        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID);
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
