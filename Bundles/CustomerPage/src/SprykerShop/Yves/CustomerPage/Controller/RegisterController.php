<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use Spryker\Shared\Customer\Code\Messages;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 */
class RegisterController extends AbstractCustomerController
{
    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function indexAction(Request $request)
    {
        $response = $this->executeIndexAction($request);

        if (!is_array($response)) {
            return $response;
        }

        return $this->view($response, [], '@CustomerPage/views/register/register.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeIndexAction(Request $request)
    {
        if ($this->isLoggedInCustomer()) {
            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_OVERVIEW);
        }

        $registerFormDataProvider = $this->getFactory()->createCustomerFormFactory()->createRegisterFormDataProvider();

        $registerForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getRegisterForm()
            ->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $customerResponseTransfer = $this->registerCustomer($registerForm->getData());

            if ($customerResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage(Messages::CUSTOMER_AUTHORIZATION_SUCCESS);

                return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_OVERVIEW);
            }

            $this->processResponseErrors($customerResponseTransfer);
        }

        $loginForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getLoginForm();

        return [
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        ];
    }

    /**
     * @param array $customerData
     *
     * @return \Generated\Shared\Transfer\CustomerResponseTransfer
     */
    protected function registerCustomer(array $customerData)
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->fromArray($customerData, true);

        $customerResponseTransfer = $this
            ->getFactory()
            ->getAuthenticationHandler()
            ->registerCustomer($customerTransfer);

        return $customerResponseTransfer;
    }
}
