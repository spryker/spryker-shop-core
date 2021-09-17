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
use SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageFactory getFactory()
 */
class RegisterController extends AbstractController
{
    /**
     * @uses \SprykerShop\Yves\CompanyPage\Plugin\Router\CompanyPageRouteProviderPlugin::ROUTE_COMPANY_OVERVIEW
     * @var string
     */
    protected const ROUTE_COMPANY_OVERVIEW = 'company/overview';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    public function indexAction(Request $request)
    {
        $response = $this->executeIndexAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CompanyPage/views/register/register.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|array
     */
    protected function executeIndexAction(Request $request)
    {
        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();

        if ($customerTransfer && $customerTransfer->getCompanyUserTransfer()) {
            return $this->redirectResponseInternal(CompanyPageRouteProviderPlugin::ROUTE_NAME_COMPANY_OVERVIEW);
        }

        $registerForm = $this
            ->getFactory()
            ->createCompanyPageFormFactory()
            ->getCompanyRegisterForm()
            ->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $companyResponseTransfer = $this->registerCompany($registerForm->getData());

            if ($companyResponseTransfer->getIsSuccessful()) {
                $this->addSuccessMessages($companyResponseTransfer);

                return $this->redirectResponseInternal(static::ROUTE_COMPANY_OVERVIEW);
            }

            foreach ($companyResponseTransfer->getMessages() as $responseMessage) {
                $this->addErrorMessage($responseMessage->getText());
            }
        }

        return [
            'registerForm' => $registerForm->createView(),
        ];
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

    /**
     * @param \Generated\Shared\Transfer\CompanyResponseTransfer $companyResponseTransfer
     *
     * @return void
     */
    protected function addSuccessMessages(CompanyResponseTransfer $companyResponseTransfer): void
    {
        if (!$companyResponseTransfer->getMessages()->count()) {
            $this->addSuccessMessage(Messages::COMPANY_AUTHORIZATION_SUCCESS);

            return;
        }

        foreach ($companyResponseTransfer->getMessages() as $responseMessageTransfer) {
            $this->addSuccessMessage($responseMessageTransfer->getText());
        }
    }
}
