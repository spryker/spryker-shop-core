<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Controller;

use SprykerShop\Yves\ShopApplication\Controller\AbstractController;
use Symfony\Component\Form\FormView;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class AuthController extends AbstractController
{
    /**
     * @return string
     */
    public function loginAction()
    {
        return $this->view([
            'loginForm' => $this->getLoginForm(),
        ], [], '@AgentPage/views/login/login.twig');
    }

    /**
     * @return \Symfony\Component\Form\FormView
     */
    protected function getLoginForm(): FormView
    {
        return $this->getFactory()
            ->createAgentLoginForm()
            ->createView();
    }
}
