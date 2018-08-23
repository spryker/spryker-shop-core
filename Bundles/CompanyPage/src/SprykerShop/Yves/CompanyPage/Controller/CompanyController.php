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
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $companyUserTransfer = $this->getCompanyUser();
        $data = [
            'company' => $this->getCompanyUser()->getCompanyBusinessUnit()->getCompany(),
        ];

        if ($this->getFactory()->createCompanyUserChecker()->isCompanyUserHasBusinessUnit($companyUserTransfer)) {
            $company = $companyUserTransfer->getCompanyBusinessUnit()->getCompany();
            $defaultBillingAddress = $this->getFactory()
                ->createCompanyBusinessAddressReader()
                ->getDefaultBillingAddress($companyUserTransfer);

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
}
