<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Controller;

use SprykerShop\Yves\CustomerPage\Plugin\Provider\CustomerPageControllerProvider;

/**
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageFactory getFactory()
 * @method \Spryker\Client\Customer\CustomerClientInterface getClient()
 */
class AuthController extends AbstractCustomerController
{
    /**
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction()
    {
        if (!$this->isLoggedInCustomer()) {
            $viewData = $this->executeLoginAction();

            return $this->view($viewData, [], '@CustomerPage/views/login/login.twig');
        }

        $redirectUrl = $this->getRedirectUrlFromPlugins();
        if ($redirectUrl) {
            return $this->redirectResponseExternal($redirectUrl);
        }

        return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_OVERVIEW);
    }

    /**
     * @return string|null
     */
    protected function getRedirectUrlFromPlugins(): ?string
    {
        $customerRedirectAfterLoginPlugins = $this->getFactory()->getAfterLoginCustomerRedirectPlugins();
        if (!$customerRedirectAfterLoginPlugins) {
            return null;
        }

        $customerTransfer = $this->getFactory()->getCustomerClient()->getCustomer();
        foreach ($customerRedirectAfterLoginPlugins as $customerRedirectAfterLoginPlugin) {
            if (!$customerRedirectAfterLoginPlugin->isApplicable($customerTransfer)) {
                continue;
            }

            return $customerRedirectAfterLoginPlugin->getRedirectUrl($customerTransfer);
        }

        return null;
    }

    /**
     * @return array
     */
    protected function executeLoginAction(): array
    {
        $loginForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getLoginForm();

        $registerFormDataProvider = $this->getFactory()->createCustomerFormFactory()->createRegisterFormDataProvider();

        $registerForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getRegisterForm($registerFormDataProvider->getOptions());

        return [
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        ];
    }
}
