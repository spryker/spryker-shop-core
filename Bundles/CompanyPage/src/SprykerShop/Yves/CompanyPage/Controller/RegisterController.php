<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer;
use Generated\Shared\Transfer\CompanyBusinessUnitTransfer;
use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyRoleTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserResponseTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerResponseTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class RegisterController extends AbstractCompanyController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        if ($this->isLoggedInCustomer()) {
            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_OVERVIEW);
        }

        $registerForm = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->getCompanyRegisterForm()
            ->handleRequest($request);

        if ($registerForm->isValid()) {
            $companyResponseTransfer = $this->registerCompany($registerForm->getData());
            $customerResponseTransfer = $this->registerCustomer($registerForm->getData());
            $companyRoleResponsetransfer = $this->registerCompanyRole($companyResponseTransfer->getCompanyTransfer()->getIdCompany());
            $companyBusinessUnitResponseTransfer = $this->registerCompanyBusinessUnit($companyResponseTransfer->getCompanyTransfer()->getIdCompany());
            $this->registerCompanyUser(
                $companyResponseTransfer->getCompanyTransfer()->getIdCompany(),
                $companyBusinessUnitResponseTransfer->getCompanyBusinessUnitTransfer()->getIdCompanyBusinessUnit(),
                $customerResponseTransfer->getCustomerTransfer()->getIdCustomer()
            );

            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_OVERVIEW);
        }

        $loginForm = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->getCompanyLoginForm();

        return $this->view([
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        ]);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CompanyResponseTransfer
     */
    protected function registerCompany(array $data): CompanyResponseTransfer
    {
        $companyName = $data['company_name'];
        $companyTransfer = new CompanyTransfer();
        $companyTransfer->setName($companyName);

        return $this->getFactory()->getCompanyClient()->createCompany($companyTransfer);
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

        return $this->getFactory()->getAuthenticationHandlerPlugin()->registerCustomer($customerTransfer);
    }

    /**
     * @param int $idCompany
     *
     * @return \Generated\Shared\Transfer\CompanyBusinessUnitResponseTransfer
     */
    protected function registerCompanyBusinessUnit($idCompany): CompanyBusinessUnitResponseTransfer
    {
        $companyBusinessUnitTransfer = new CompanyBusinessUnitTransfer();
        $companyBusinessUnitTransfer->setFkCompany($idCompany)->setName('default');

        return $this->getFactory()->getCompanyBusinessUnitClient()->createCompanyBusinessUnit($companyBusinessUnitTransfer);
    }

    /**
     * @param int $companyId
     * @param int $companyBusinessUnitId
     * @param int $customerId
     *
     * @return \Generated\Shared\Transfer\CompanyUserResponseTransfer
     */
    protected function registerCompanyUser($companyId, $companyBusinessUnitId, $customerId): CompanyUserResponseTransfer
    {
        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->setFkCompany($companyId);
        $companyUserTransfer->setFkCustomer($customerId);
        $companyUserTransfer->setFkCompanyBusinessUnit($companyBusinessUnitId);

        return $this->getFactory()->getCompanyUserClient()->createCompanyUser($companyUserTransfer);
    }

    protected function registerCompanyRole($idCompany)
    {
        $companyRoleTransfer = new CompanyRoleTransfer();
        $companyRoleTransfer->setFkCompany($idCompany);
        $companyRoleTransfer->setName('admin');

        return $this->getFactory()->getCompanyRoleClient()->createCompanyRole($companyRoleTransfer);
    }

}
