<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;
use SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 */
class RegisterController extends AbstractCustomerController
{
    protected const MESSAGE_CUSTOMER_CONFIRMED = 'customer.registration.confirmed';

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

        $registerForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getRegisterForm()
            ->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            $customerResponseTransfer = $this->registerCustomer($registerForm->getData());

            if ($customerResponseTransfer->getIsSuccess()) {
                $this->addSuccessMessage($customerResponseTransfer->getSuccessMessage());

                return $this->redirectResponseInternal($customerResponseTransfer->getSuccessRedirectRoute());
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function confirmAction(Request $request): RedirectResponse
    {
        $response = $this->executeConfirmAction($request);

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    protected function executeConfirmAction(Request $request): RedirectResponse
    {
        $customerTransfer = new CustomerTransfer();
        $customerTransfer->setRegistrationKey($request->request->get('token'));

        $this->getClient()->confirmRegistration($customerTransfer);

        $this->addSuccessMessage(static::MESSAGE_CUSTOMER_CONFIRMED);

        return $this->redirectResponseInternal(CustomerPageRouteProviderPlugin::ROUTE_LOGIN);
    }
}
