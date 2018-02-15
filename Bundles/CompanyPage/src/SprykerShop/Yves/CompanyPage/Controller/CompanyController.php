<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
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
        if (!$this->isLoggedInCustomer()) {
            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_REGISTER);
        }

        $data = [];
        return $this->view($data, $this->getFactory()->getCompanyOverviewWidgetPlugins());
    }

    protected function isCompanyActive(): bool
    {
        $companyUser = $this->getCompanyUser();

        if ($companyUser === null) {
            return false;
        }

        $idCompany = $companyUser->getFkCompany();
        $this->getFactory()->getCompanyClient();
    }

}
