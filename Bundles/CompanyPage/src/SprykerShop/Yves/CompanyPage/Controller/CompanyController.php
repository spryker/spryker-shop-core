<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressCriteriaFilterTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
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
        $company = $this->getCompanyUser()->getCompanyBusinessUnit()->getCompany();
        $companyUnitAddressTransfer = $this->createCompanyUnitAddressTransfer();
        $defaultBillingAddress = $this->getFactory()->getCompanyUnitAddressClient()->getCompanyUnitAddressById($companyUnitAddressTransfer);

        $data = [
            'company' => $company,
            'defaultBillingAddress' => $defaultBillingAddress
        ];

        return $this->view(
            $data,
            $this->getFactory()->getCompanyOverviewWidgetPlugins(),
            '@CompanyPage/views/overview/overview.twig'
        );
    }

    /**
     * @return \Generated\Shared\Transfer\CompanyUnitAddressTransfer
     */
    protected function createCompanyUnitAddressTransfer(): CompanyUnitAddressTransfer
    {
        $idDefaultBillingAddress = $this->getCompanyUser()->getCompanyBusinessUnit()->getDefaultBillingAddress();

        return (new CompanyUnitAddressTransfer())->
            setIdCompanyUnitAddress($idDefaultBillingAddress);
    }
}
