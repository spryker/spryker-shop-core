<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
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
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction()
    {
        if ($this->isLoggedInCustomer()) {
            return $this->redirectResponseInternal(CustomerPageControllerProvider::ROUTE_CUSTOMER_OVERVIEW);
        }

        $loginForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->createLoginForm();
        $registerForm = $this
            ->getFactory()
            ->createCustomerFormFactory()
            ->createRegisterForm();

        return [
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        ];
    }
}
