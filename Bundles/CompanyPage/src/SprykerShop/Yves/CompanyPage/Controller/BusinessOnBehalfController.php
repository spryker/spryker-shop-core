<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyUserCollectionTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\CompanyPage\Form\CompanyUserAccountForm;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

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
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        $companyUserAccountFormDataProvider = $this->getFactory()->createCompanyPageFormFactory()->createCompanyUserAccountDataProvider();
        $activeCompanyUsers = $this->getFactory()->getBusinessOnBehalfClient()->findActiveCompanyUsersByCustomerId($customerTransfer);

        $companyUserAccountForm = $this->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyUserAccountForm(
                $companyUserAccountFormDataProvider->getData($customerTransfer),
                $companyUserAccountFormDataProvider->getOptions($activeCompanyUsers)
            )
            ->handleRequest($request);

        if ($companyUserAccountForm->isSubmitted() && $companyUserAccountForm->isValid()) {
            $isDefault = (bool)$request->request->get(CompanyUserAccountForm::FORM_NAME)[CompanyUserAccountForm::FIELD_IS_DEFAULT];
            $this->saveCompanyUser($customerTransfer, $activeCompanyUsers, $companyUserAccountForm->getData(), $isDefault);

        }

        $data = [
            'form' => $companyUserAccountForm->createView(),
        ];

        return $this->view($data, [], '@CompanyPage/views/user-select/user-select.twig');
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer $customerTransfer
     * @param \Generated\Shared\Transfer\CompanyUserCollectionTransfer $companyUserCollectionTransfer
     * @param array $formData
     * @param bool $isDefault
     *
     * @return void
     */
    protected function saveCompanyUser(
        CustomerTransfer $customerTransfer,
        CompanyUserCollectionTransfer $companyUserCollectionTransfer,
        array $formData,
        bool $isDefault = false
    ): void
    {
        $idCompanyUserSelected = $formData[CompanyUserAccountForm::FIELD_COMPANY_USER_ACCOUNT_CHOICE];

        foreach ($companyUserCollectionTransfer->getCompanyUsers() as $companyUser) {
            if ($this->updateCustomerInSession($companyUser, $idCompanyUserSelected)) {
                if ($isDefault) {
                    $this->getFactory()->getBusinessOnBehalfClient()->setDefaultCompanyUser($companyUser);
                }
                return;
            }
        }
        $this->addErrorMessage(static::ERROR_COMPANY_USER_INVALID);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyUserTransfer $companyUser
     * @param int $idCompanyUserSelected
     *
     * @return bool
     */
    protected function updateCustomerInSession(CompanyUserTransfer $companyUser, int $idCompanyUserSelected): bool
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        if ($companyUser->getIdCompanyUser() === $idCompanyUserSelected) {
            if ($companyUser->getCompany()->getIsActive()) {
                $customerTransfer->setCompanyUserTransfer($companyUser);
                $this->getFactory()->getCustomerClient()->setCustomer($customerTransfer);

                return true;
            }
            $this->addErrorMessage(static::ERROR_COMPANY_NOT_ACTIVE);
        }

        return false;
    }
}
