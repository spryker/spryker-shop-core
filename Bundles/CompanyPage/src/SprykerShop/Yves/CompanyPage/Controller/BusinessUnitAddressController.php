<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

class BusinessUnitAddressController extends AbstractCompanyController
{
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

        if ($addressForm->isSubmitted() === false) {
            $idCompanyBusinessUnit = $request->query->getInt('id');

            $addressForm->setData(
                $dataProvider->getData(
                    $this->getCompanyUser(),
                    null,
                    $idCompanyBusinessUnit
                )
            );
        }

        if ($addressForm->isValid()) {
            $idCompanyBusinessUnit = $request->query->getInt('id');
            $companyUnitAddressTransfer = $this->saveAddress($addressForm->getData());
            $this->saveCompanyBusinessUnitAddress(
                $companyUnitAddressTransfer,
                $idCompanyBusinessUnit
            );

            if ($companyUnitAddressTransfer) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT);
            }
        }

        return [
            'form' => $addressForm->createView(),
        ];
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
     * @param \Generated\Shared\Transfer\CompanyUnitAddressTransfer $companyUnitAddressTransfer
     * @param int $idCompanyBusinessUnit
     *
     * @return void
     */
    protected function saveCompanyBusinessUnitAddress(
        CompanyUnitAddressTransfer $companyUnitAddressTransfer,
        int $idCompanyBusinessUnit
    ): void {
        $addressCollection = new CompanyUnitAddressCollectionTransfer();
        $addressCollection->addCompanyUnitAddress($companyUnitAddressTransfer);

        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setIdCompanyBusinessUnit($idCompanyBusinessUnit)
            ->setAddressCollection($addressCollection);

        $this->getFactory()
            ->getCompanyUnitAddressClient()
            ->saveCompanyBusinessUnitAddresses($companyBusinessUnitTransfer);
    }
}
