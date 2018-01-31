<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Controller;

use SprykerShop\Yves\CompanyPage\Plugin\Provider\CompanyPageControllerProvider;

class AuthController extends AbstractCompanyController
{
    /**
     * @return array|\Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction()
    {
        if ($this->isLoggedInCustomer()) {
            return $this->redirectResponseInternal(CompanyPageControllerProvider::ROUTE_COMPANY_OVERVIEW);
        }

        $loginForm = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->getCompanyLoginForm();
        $registerForm = $this
            ->getFactory()
            ->createCompanyFormFactory()
            ->getCompanyRegisterForm();

        return $this->view([
            'loginForm' => $loginForm->createView(),
            'registerForm' => $registerForm->createView(),
        ]);
    }
}
