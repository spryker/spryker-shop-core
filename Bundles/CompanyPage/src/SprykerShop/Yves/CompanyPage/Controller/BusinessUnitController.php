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
use SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class BusinessUnitController extends AbstractCompanyController
{
    /**
     * @var string
     */
    protected const MESSAGE_FORM_CSRF_VALIDATION_ERROR = 'form.csrf.error.text';
    /**
     * @var string
     */
    protected const BUSINESS_UNIT_LIST_SORT_FIELD = 'id_company_business_unit';
    /**
     * @var string
     */
    protected const COMPANY_UNIT_ADDRESS_LIST_SORT_FIELD = 'id_company_unit_address';
    /**
     * @var string
     */
    protected const REQUEST_PARAM_ID = 'id';

    /**
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array
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
            'businessUnitsTree' => $this->getFactory()->createCompanyBusinessUnitTreeBuilder()->getCustomerCompanyBusinessUnitTree(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function detailsAction(Request $request)
    {
        $viewData = $this->getCompanyBusinessUnitDetailsResponseData($request);

        return $this->view($viewData, [], '@CompanyPage/views/business-unit-detail/business-unit-detail.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array
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
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeCreateAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->createBusinessUnitFormDataProvider();

        $companyUserTransfer = $this->findCurrentCompanyUserTransfer();
        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID);
        $dataProviderOptions = $dataProvider->getOptions($companyUserTransfer, $idCompanyBusinessUnit);

        $companyBusinessUnitForm = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getBusinessUnitForm($dataProviderOptions)
            ->handleRequest($request);

        if ($companyBusinessUnitForm->isSubmitted() === false) {
            $companyBusinessUnitForm->setData($dataProvider->getData($this->findCurrentCompanyUserTransfer()));
        }

        if ($companyBusinessUnitForm->isSubmitted() === true && $companyBusinessUnitForm->isValid() === true) {
            $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitSave($companyBusinessUnitForm->getData());

            if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
                $this->applySuccessMessage($companyBusinessUnitResponseTransfer);
            }

            if (!$companyBusinessUnitResponseTransfer->getIsSuccessful()) {
                $this->applyErrorMessage($companyBusinessUnitResponseTransfer);
            }

            return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT_UPDATE, [
                'id' => $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getIdCompanyBusinessUnit(),
            ]);
        }

        return [
            'form' => $companyBusinessUnitForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array
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
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeUpdateAction(Request $request)
    {
        $dataProvider = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->createBusinessUnitFormDataProvider();

        $companyUserTransfer = $this->findCurrentCompanyUserTransfer();
        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID);
        $dataProviderOptions = $dataProvider->getOptions($companyUserTransfer, $idCompanyBusinessUnit);

        $companyBusinessUnitForm = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getBusinessUnitForm($dataProviderOptions)
            ->handleRequest($request);

        if ($companyBusinessUnitForm->isSubmitted() === false) {
            $data = $dataProvider->getData($this->findCurrentCompanyUserTransfer(), $idCompanyBusinessUnit);

            $companyBusinessUnitTransfer = $this->getFactory()
                ->getCompanyBusinessUnitClient()
                ->getCompanyBusinessUnitById((new CompanyBusinessUnitTransfer())->setIdCompanyBusinessUnit($idCompanyBusinessUnit));

            if (!$this->isCurrentCustomerRelatedToCompany($companyBusinessUnitTransfer->getFkCompany())) {
                throw new NotFoundHttpException();
            }

            $companyBusinessUnitForm->setData($data);
        }

        if ($companyBusinessUnitForm->isSubmitted() === true && $companyBusinessUnitForm->isValid() === true) {
            $companyBusinessUnitResponseTransfer = $this->companyBusinessUnitSave($companyBusinessUnitForm->getData());

            if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
                $this->applySuccessMessage($companyBusinessUnitResponseTransfer);
            }

            if (!$companyBusinessUnitResponseTransfer->getIsSuccessful()) {
                $this->applyErrorMessage($companyBusinessUnitResponseTransfer);
            }

            return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT_UPDATE, [
                'id' => $idCompanyBusinessUnit,
            ]);
        }

        return [
            'form' => $companyBusinessUnitForm->createView(),
            'addresses' => $dataProviderOptions[CompanyBusinessUnitForm::FIELD_COMPANY_UNIT_ADDRESSES],
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
        $companyBusinessUnitDeleteForm = $this->getFactory()->createCompanyPageFormFactory()->getCompanyBusinessUnitDeleteForm()->handleRequest($request);

        if (!$companyBusinessUnitDeleteForm->isSubmitted() || !$companyBusinessUnitDeleteForm->isValid()) {
            $this->addErrorMessage(static::MESSAGE_FORM_CSRF_VALIDATION_ERROR);

            return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT);
        }

        $companyBusinessUnitId = $request->query->getInt(static::REQUEST_PARAM_ID);
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($companyBusinessUnitId);

        $companyBusinessUnitTransfer = $this->getFactory()
            ->getCompanyBusinessUnitClient()
            ->getCompanyBusinessUnitById($companyBusinessUnitTransfer);

        if (!$this->isCurrentCustomerRelatedToCompany($companyBusinessUnitTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

        $companyBusinessUnitResponseTransfer = $this->getFactory()
            ->getCompanyBusinessUnitClient()
            ->deleteCompanyBusinessUnit($companyBusinessUnitTransfer);

        if ($companyBusinessUnitResponseTransfer->getIsSuccessful()) {
            $this->applySuccessMessage($companyBusinessUnitResponseTransfer);
        }

        if (!$companyBusinessUnitResponseTransfer->getIsSuccessful()) {
            $this->applyErrorMessage($companyBusinessUnitResponseTransfer);
        }

        return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function confirmDeleteAction(Request $request)
    {
        $viewData = $this->executeConfirmDeleteAction($request);

        return $this->view(
            $viewData,
            [],
            '@CompanyPage/views/business-unit-delete-confirmation-page/business-unit-delete-confirmation-page.twig'
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return array
     */
    protected function executeConfirmDeleteAction(Request $request): array
    {
        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID);
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit);

        $companyBusinessUnitTransfer = $this->getFactory()
            ->getCompanyBusinessUnitClient()
            ->getCompanyBusinessUnitById($companyBusinessUnitTransfer);

        if (!$this->isCurrentCustomerRelatedToCompany($companyBusinessUnitTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

        return [
            'companyBusinessUnitDeleteForm' => $this->getFactory()->createCompanyPageFormFactory()->getCompanyBusinessUnitDeleteForm()->createView(),
            'companyBusinessUnit' => $companyBusinessUnitTransfer,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitCriteriaFilterTransfer
     */
    protected function createBusinessUnitCriteriaFilterTransfer(Request $request): CompanyBusinessUnitCriteriaFilterTransfer
    {
        $criteriaFilterTransfer = new CompanyBusinessUnitCriteriaFilterTransfer();

        $criteriaFilterTransfer->setIdCompany($this->findCurrentCompanyUserTransfer()->getFkCompany());

        $filterTransfer = $this->createFilterTransfer(self::BUSINESS_UNIT_LIST_SORT_FIELD);
        $criteriaFilterTransfer->setFilter($filterTransfer);

        $paginationTransfer = $this->createPaginationTransfer($request);
        $criteriaFilterTransfer->setPagination($paginationTransfer);

        return $criteriaFilterTransfer;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
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

        if (!$this->isCurrentCustomerRelatedToCompany($companyBusinessUnitTransfer->getFkCompany())) {
            throw new NotFoundHttpException();
        }

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
            'companyUnitAddressDeleteFormCloner' => $this->getFactory()->createFormCloner()
                ->setForm($this->getFactory()->createCompanyPageFormFactory()->getCompanyUnitAddressDeleteForm()),
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
        $criteriaFilterTransfer->setIdCompany($this->findCurrentCompanyUserTransfer()->getFkCompany());

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
    protected function companyBusinessUnitSave(array $data): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->fromArray($data, true);

        $companyBusinessUnitClient = $this->getFactory()->getCompanyBusinessUnitClient();

        if ($companyBusinessUnitTransfer->getIdCompanyBusinessUnit()) {
            return $companyBusinessUnitClient->updateCompanyBusinessUnit($companyBusinessUnitTransfer);
        }

        return $companyBusinessUnitClient->createCompanyBusinessUnit($companyBusinessUnitTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer
     *
     * @return void
     */
    protected function applyErrorMessage(CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer): void
    {
        foreach ($companyBusinessUnitResponseTransfer->getMessages() as $message) {
            $this->addTranslatedErrorMessage($message->getText(), [
                '%unit%' => $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getName(),
            ]);
        }
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer
     *
     * @return void
     */
    protected function applySuccessMessage(CompanyBusinessUnitResponseTransfer $companyBusinessUnitResponseTransfer): void
    {
        foreach ($companyBusinessUnitResponseTransfer->getMessages() as $message) {
            $this->addTranslatedSuccessMessage($message->getText(), [
                '%unit%' => $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getName(),
            ]);
        }
    }
}
