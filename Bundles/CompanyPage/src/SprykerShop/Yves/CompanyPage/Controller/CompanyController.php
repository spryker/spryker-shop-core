<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyController extends AbstractCompanyController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $companyUserTransfer = $this->getCompanyUser();
        $defaultBillingAddress = null;
        $data = [
            'company' => $this->getCompanyUser()->getCompanyBusinessUnit()->getCompany(),
        ];

        if ($companyUserTransfer && $companyUserTransfer->getCompanyBusinessUnit()) {
            $company = $companyUserTransfer->getCompanyBusinessUnit()->getCompany();
            $companyUnitAddressTransfer = $this->createCompanyUnitAddressTransfer($companyUserTransfer);

            if ($companyUnitAddressTransfer->getIdCompanyUnitAddress()) {
                $defaultBillingAddress = $this->getFactory()
                    ->getCompanyUnitAddressClient()
                    ->getCompanyUnitAddressById($companyUnitAddressTransfer);
            }

            $data = [
                'company' => $company,
                'defaultBillingAddress' => $defaultBillingAddress,
            ];
        }

        return $this->view(
            $data,
            $this->getFactory()->getCompanyOverviewWidgetPlugins(),
            '@CompanyPage/views/overview/overview.twig'
        );
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUserTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    protected function createCompanyUnitAddressTransfer(CompanyUserTransfer $companyUserTransfer): CompanyUnitAddressTransfer
    {
        $fkDefaultBillingAddress = $companyUserTransfer->getCompanyBusinessUnit()->getDefaultBillingAddress();

        $companyUnitAddressTransfer = new CompanyUnitAddressTransfer();

        if ($fkDefaultBillingAddress === null) {
            return $companyUnitAddressTransfer;
        }

        return $companyUnitAddressTransfer
            ->setIdCompanyUnitAddress($fkDefaultBillingAddress);
    }
}
