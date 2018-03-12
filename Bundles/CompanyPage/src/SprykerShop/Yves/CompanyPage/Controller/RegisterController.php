<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use Generated\Shared\Transfer\CompanyResponseTransfer;
use Generated\Shared\Transfer\CompanyTransfer;
use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Company\Code\Messages;
use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class RegisterController extends AbstractController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $registerForm = $this
            ->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyRegisterForm()
            ->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $companyResponseTransfer = $this->registerCompany($registerForm->getData());

            if ($companyResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessage(Messages::COMPANY_AUTHORIZATION_SUCCESS);

                return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_OVERVIEW);
            }

            $this->processResponseMessages($companyResponseTransfer);
        }

        $data = [
            'registerForm' => $registerForm->createView(),
        ];

        return $this->view($data, [], '@CompanyPage/views/register/register.twig');
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
        $companyTransfer->setInitialUserTransfer($this->createCompanyUserTransfer($data));

        $companyTransfer->setName($companyName);

        return $this->getFactory()->getCompanyClient()->createCompany($companyTransfer);
    }

    /**
     * @param array $data
     *
     * @return \Generated\Shared\Transfer\CompanyUserTransfer
     */
    protected function createCompanyUserTransfer(array $data): CompanyUserTransfer
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($data, true);

        $companyUserTransfer = new CompanyUserTransfer();
        $companyUserTransfer->setCustomer($customerTransfer);

        return $companyUserTransfer;
    }
}
