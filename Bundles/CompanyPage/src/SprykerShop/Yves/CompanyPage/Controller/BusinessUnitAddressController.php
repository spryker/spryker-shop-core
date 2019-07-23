<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use SprykerShop\Yves\CompanyPage\Form\CompanyBusinessUnitAddressForm;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BusinessUnitAddressController extends AbstractCompanyController
{
    public const REQUEST_COMPANY_BUSINESS_UNIT_ID = 'id';

    protected const REQUEST_PARAM_ID_COMPANY_BUSINESS_UNIT = 'idCompanyBusinessUnit';

    protected const MESSAGE_BUSINESS_UNIT_ADDRESS_CREATE_SUCCESS = 'message.business_unit_address.create';
    protected const MESSAGE_BUSINESS_UNIT_ADDRESS_UPDATE_SUCCESS = 'message.business_unit_address.update';
    protected const GLOSSARY_MESSAGE_PARAM_ADDRESS = '%address%';

    /**
     * @uses \SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT
     */
    protected const ROUTE_COMPANY_BUSINESS_UNIT = 'company/business-unit';

    /**
     * @uses \SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE
     */
    protected const ROUTE_COMPANY_BUSINESS_UNIT_UPDATE = 'company/business-unit/update';

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

        return $this->view($response, [], '@CompanyPage/views/business-unit-address-create/business-unit-address-create.twig');
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

        return $this->view($response, [], '@CompanyPage/views/business-unit-address-update/business-unit-address-update.twig');
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
            ->getCompanyBusinessUnitAddressForm($dataProvider->getOptions())
            ->handleRequest($request);

        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_COMPANY_BUSINESS_UNIT_ID);

        if ($addressForm->isSubmitted() === false) {
            $addressForm->setData(
                $dataProvider->getData(
                    $this->findCurrentCompanyUserTransfer(),
                    null,
                    $idCompanyBusinessUnit
                )
            );
        }

        if ($addressForm->isSubmitted() === true && $addressForm->isValid() === true) {
            $data = $addressForm->getData();
            $data[CompanyUnitAddressTransfer::COMPANY_BUSINESS_UNITS][CompanyBusinessUnitCollectionTransfer::COMPANY_BUSINESS_UNITS][][CompanyBusinessUnitTransfer::ID_COMPANY_BUSINESS_UNIT] = $idCompanyBusinessUnit;

            $companyUnitAddressTransfer = $this->getFactory()
                ->createCompanyBusinessAddressSaver()
                ->saveAddress($data);

            if ($companyUnitAddressTransfer->getIdCompanyUnitAddress()) {
                $this->addTranslatedSuccessMessage(static::MESSAGE_BUSINESS_UNIT_ADDRESS_CREATE_SUCCESS, [
                    '%address%' => $companyUnitAddressTransfer->getAddress1(),
                ]);

                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE, [
                    'id' => $idCompanyBusinessUnit,
                ]);
            }
        }

        return [
            'form' => $addressForm->createView(),
            'idCompanyBusinessUnit' => $idCompanyBusinessUnit,
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeUpdateAction(Request $request)
    {
        $idCompanyUnitAddress = $request->query->getInt(static::REQUEST_COMPANY_BUSINESS_UNIT_ID);
        $idCompanyBusinessUnit = $request->query->getInt(static::REQUEST_PARAM_ID_COMPANY_BUSINESS_UNIT);

        if (!$idCompanyBusinessUnit || !$idCompanyUnitAddress) {
            return $this->redirectResponseInternal(static::ROUTE_COMPANY_BUSINESS_UNIT);
        }

        $companyBusinessUnitAddressForm = $this->getCompanyBusinessUnitAddressForm($idCompanyUnitAddress, $idCompanyBusinessUnit)
            ->handleRequest($request);

        $this->assertCurrentCustomerRelatedToCompany($companyBusinessUnitAddressForm);

        if ($companyBusinessUnitAddressForm->isSubmitted() && $companyBusinessUnitAddressForm->isValid()) {
            $companyUnitAddressResponseTransfer = $this->updateCompanyBusinessUnitAddress($companyBusinessUnitAddressForm);

            if ($companyUnitAddressResponseTransfer->getIsSuccessful()) {
                $this->addTranslatedSuccessMessage(static::MESSAGE_BUSINESS_UNIT_ADDRESS_UPDATE_SUCCESS, [
                    static::GLOSSARY_MESSAGE_PARAM_ADDRESS => $companyUnitAddressResponseTransfer->getCompanyUnitAddressTransfer()->getAddress1(),
                ]);

                return $this->redirectResponseInternal(static::ROUTE_COMPANY_BUSINESS_UNIT_UPDATE, [
                    static::REQUEST_COMPANY_BUSINESS_UNIT_ID => $idCompanyBusinessUnit,
                ]);
            }

            $this->addCompanyUnitAddressResponseErrorMessages($companyUnitAddressResponseTransfer);
        }

        return [
            'form' => $companyBusinessUnitAddressForm->createView(),
            'idCompanyBusinessUnit' => $idCompanyBusinessUnit,
            'idCompanyUnitAddress' => $idCompanyUnitAddress,
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyBusinessUnitAddressForm
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer
     */
    protected function updateCompanyBusinessUnitAddress(FormInterface $companyBusinessUnitAddressForm): CompanyUnitAddressResponseTransfer
    {
        return $this->getFactory()
            ->getCompanyUnitAddressClient()
            ->updateCompanyUnitAddress(
                (new CompanyUnitAddressTransfer())
                    ->fromArray($companyBusinessUnitAddressForm->getData())
            );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer
     *
     * @return void
     */
    protected function addCompanyUnitAddressResponseErrorMessages(CompanyUnitAddressResponseTransfer $companyUnitAddressResponseTransfer): void
    {
        foreach ($companyUnitAddressResponseTransfer->getMessages() as $responseMessageTransfer) {
            $this->addErrorMessage($responseMessageTransfer->getText());
        }
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $companyBusinessUnitAddressForm
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function assertCurrentCustomerRelatedToCompany(FormInterface $companyBusinessUnitAddressForm): void
    {
        if (!$this->isCurrentCustomerRelatedToCompany($companyBusinessUnitAddressForm->getData()[CompanyBusinessUnitAddressForm::FIELD_FK_COMPANY])) {
            throw new NotFoundHttpException();
        }
    }

    /**
     * @param int $idCompanyUnitAddress
     * @param int $idCompanyBusinessUnit
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    protected function getCompanyBusinessUnitAddressForm(int $idCompanyUnitAddress, int $idCompanyBusinessUnit): FormInterface
    {
        $companyUnitAddressFormDataProvider = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->createCompanyUnitAddressFormDataProvider();

        $companyUnitAddressFormData = $companyUnitAddressFormDataProvider->getData(
            $this->findCurrentCompanyUserTransfer(),
            $idCompanyUnitAddress,
            $idCompanyBusinessUnit
        );

        return $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyBusinessUnitAddressForm(
                $companyUnitAddressFormDataProvider->getOptions(),
                $companyUnitAddressFormData
            );
    }
}
