<?php

namespace SprykerShop\Yves\CompanyUserPage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\CompanyUserPage\CompanyUserPageFactory getFactory()
 */
class BusinessOnBehalfController extends AbstractController
{
    public function selectCompanyUserAction()
    {
        $companyUserAccountFormDataProvider = $this->getFactory()->createCompanyUserAccountDataProvider();
        $companyUserAccountForm = $this->getFactory()->createCompanyUserPageFormFactory()->getCompanyUserAccountForm(
            $companyUserAccountFormDataProvider->getOptions()
        );

        $data = [
            'form' => $companyUserAccountForm->createView(),
        ];
        return $this->view($data, [], '@CompanyUserPage/views/company-user/change.twig');
    }
}
