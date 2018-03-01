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
        if ($this->isLoggedInCustomer()) {
            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_OVERVIEW);
        }

        $loginForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getLoginForm();
        $registerForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->getRegisterForm();

        return $this->view(
            [
                'loginForm' => $loginForm->createView(),
                'registerForm' => $registerForm->createView(),
            ],
            [],
            '@CustomerPage/views/login/login.twig'
        );
    }
}
