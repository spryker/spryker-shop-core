<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class CreateController extends AbstractCompanyController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if ($this->isLoggedInCustomer()) {
            //return $this->redirectResponseInternal('/');
        }

        $companyCreateForm = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->createCompanyCreateForm()
            ->handleRequest($request);

        if ($companyCreateForm->isValid()) {
            $companyTransfer = $this->createCompany($companyCreateForm->getData());
            //$customerResponseTransfer = $this->registerCustomer($companyCreateForm->getData());
            //$CompanyUserResponseTransfer = $this->createCompanyUser($companyTransfer, $customerResponseTransfer);
        }

        return $this->view([
            'companyCreateForm' => $companyCreateForm->createView(),
        ]);
    }

    /**
     * @param \Generated\Shared\Transfer\CompanyTransfer $companyTransfer
     * @param \Generated\Shared\Transfer\CustomerResponseTransfer $customerResponseTransfer
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function createCompanyUser(
        CompanyTransfer $companyTransfer,
        CustomerResponseTransfer $customerResponseTransfer
    ): CompanyUserResponseTransfer {
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->setFkCompany($companyTransfer->getIdCompany())
            ->setFkCustomer($customerResponseTransfer->getCustomerTransfer()->getIdCustomer());

        return $this->getFactory()->getCompanyUserClient()->createCompanyUser($companyUserTransfer);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CompanyTransfer
     */
    protected function createCompany(array $data): CompanyTransfer
    {
        $companyTransfer = new CompanyTransfer();
        $companyTransfer->setName($data['company_name']);
        $companyTransfer = $this->getFactory()->getCompanyClient()->createCompany($companyTransfer);

        return $companyTransfer;
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function registerCustomer(array $data): CustomerResponseTransfer
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($data, true);

        $customerResponseTransfer = $this
            ->getFactory()
            ->getAuthenticationHandler()
            ->registerCustomer($customerTransfer);

        return $customerResponseTransfer;
    }
}
