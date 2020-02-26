<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\CompanyPage\Form\CompanyUserAccountSelectorForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class BusinessOnBehalfController extends AbstractController
{
    protected const ERROR_COMPANY_NOT_ACTIVE = 'company_user.business_on_behalf.error.company_not_active';
    protected const ERROR_COMPANY_USER_INVALID = 'company_user.business_on_behalf.error.company_user_invalid';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function selectCompanyUserAction(Request $request): View
    {
        $viewData = $this->executeSelectCompanyUserAction($request);

        return $this->view($viewData, [], '@CompanyPage/views/user-select/user-select.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array
     */
    protected function executeSelectCompanyUserAction(Request $request): array
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        $this->assertCompanyUserChangeAllowed($customerTransfer);

        $companyUserAccountFormDataProvider = $this->getFactory()->createCompanyPageFormFactory()->createCompanyUserAccountDataProvider();
        $activeCompanyUsers = $this->getFactory()->getBusinessOnBehalfClient()->findActiveCompanyUsersByCustomerId($customerTransfer);

        $companyUserAccountForm = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyUserAccountForm(
                $companyUserAccountFormDataProvider->getData($customerTransfer),
                $companyUserAccountFormDataProvider->getOptions($activeCompanyUsers, $customerTransfer)
            )
            ->handleRequest($request);

        if ($companyUserAccountForm->isSubmitted() && $companyUserAccountForm->isValid()) {
            $isDefault = $this->isDefaultFieldSelected($request);
            $this->getFactory()->createCompanyUserSaver()->saveCompanyUser($activeCompanyUsers, $companyUserAccountForm->getData(), $isDefault);
        }

        return [
            'form' => $companyUserAccountForm->createView(),
        ];
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isDefaultFieldSelected(Request $request): bool
    {
        $isDefault = false;
        if (
            $request->request->has(CompanyUserAccountSelectorForm::FORM_NAME)
            && isset($request->request->get(CompanyUserAccountSelectorForm::FORM_NAME)[CompanyUserAccountSelectorForm::FIELD_IS_DEFAULT])
            && $request->request->get(CompanyUserAccountSelectorForm::FORM_NAME)[CompanyUserAccountSelectorForm::FIELD_IS_DEFAULT]
        ) {
            $isDefault = true;
        }

        return $isDefault;
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function assertCompanyUserChangeAllowed(CustomerTransfer $customerTransfer): void
    {
        $isAllowed = $this->getFactory()
            ->getBusinessOnBehalfClient()
            ->isCompanyUserChangeAllowed($customerTransfer);

        if (!$isAllowed) {
            throw new NotFoundHttpException('You are not allowed to access this page.');
        }
    }
}
