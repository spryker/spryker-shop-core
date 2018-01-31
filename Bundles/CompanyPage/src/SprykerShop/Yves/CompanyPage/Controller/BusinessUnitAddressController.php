<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

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
        $dataProvider = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->createCompanyUnitAddressFormDataProvider();

        $addressForm = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->getCompanyBusinessUnitAddressForm($dataProvider->getOptions())
            ->handleRequest($request);

        if ($addressForm->isSubmitted() === false) {
            $idCompanyBusinessUnit = $request->query->getInt('id');

            $companyUserTransfer = $this->getCompanyUser();
            $addressForm->setData($dataProvider->getData($companyUserTransfer, null, $idCompanyBusinessUnit));
        }

        if ($addressForm->isValid()) {
            $companyUnitAddressTransfer = $this->saveAddress($addressForm->getData());

            if ($companyUnitAddressTransfer) {
                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_BUSINESS_UNIT);
            }
        }

        return $this->view([
            'form' => $addressForm->createView(),
        ]);
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
}
