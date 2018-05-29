<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class BusinessOnBehalfController extends AbstractController
{
    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function selectCompanyUserAction(): View
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $companyUserAccountFormDataProvider = $this->getFactory()->createCompanyPageFormFactory()->createCompanyUserAccountDataProvider();
        $companyUserAccountForm = $this->getFactory()->createCompanyPageFormFactory()->getCompanyUserAccountForm(
            $companyUserAccountFormDataProvider->getData($customerTransfer),
            $companyUserAccountFormDataProvider->getOptions($customerTransfer)
        );

        $data = [
            'form' => $companyUserAccountForm->createView(),
        ];
        return $this->view($data, [], '@CompanyPage/views/user-select/user-select.twig');
    }
}
