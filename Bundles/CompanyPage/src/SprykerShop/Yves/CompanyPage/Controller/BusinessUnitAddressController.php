<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitCollectionTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class BusinessUnitAddressController extends AbstractCompanyController
{
    public const REQUEST_COMPANY_BUSINESS_UNIT_ID = 'id';

    protected const MESSAGE_BUSINESS_UNIT_ADDRESS_CREATE_SUCCESS = 'message.business_unit_address.create';

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

        if ($addressForm->isValid()) {
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
}
