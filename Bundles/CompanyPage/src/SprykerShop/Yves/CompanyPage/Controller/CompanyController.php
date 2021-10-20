<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CompanyController extends AbstractCompanyController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $companyUserTransfer = $this->findCurrentCompanyUserTransfer();
        $data = [
            'company' => $this->findCurrentCompanyUserTransfer()->getCompanyBusinessUnit()->getCompany(),
        ];

        if ($this->getFactory()->createCompanyUserValidator()->hasBusinessUnit($companyUserTransfer)) {
            $company = $companyUserTransfer->getCompanyBusinessUnit()->getCompany();
            $defaultBillingAddress = $this->getFactory()
                ->createCompanyBusinessUnitAddressReader()
                ->getDefaultBillingAddress($companyUserTransfer);

            $data = [
                'company' => $company,
                'defaultBillingAddress' => $defaultBillingAddress,
            ];
        }

        return $this->view(
            $data,
            $this->getFactory()->getCompanyOverviewWidgetPlugins(),
            '@CompanyPage/views/overview/overview.twig',
        );
    }
}
