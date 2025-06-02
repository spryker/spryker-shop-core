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
     * @uses \SprykerShop\Yves\CustomerPage\Plugin\Router\CustomerPageRouteProviderPlugin::ROUTE_CUSTOMER_OVERVIEW
     *
     * @var string
     */
    protected const ROUTE_CUSTOMER_OVERVIEW = 'customer/overview';

    /**
     * @uses \SprykerShop\Yves\AgentPage\Plugin\Router\AgentPageRouteProviderPlugin::ROUTE_AGENT_OVERVIEW
     *
     * @var string
     */
    protected const ROUTE_AGENT_OVERVIEW = 'agent/overview';

    /**
     * @var string
     */
    protected const GLOSSARY_KEY_CUSTOMER_ALREADY_LOGGED_IN = 'agent_page.error.customer_already_logged_in';

    /**
     * @return \Spryker\Yves\Kernel\View\View|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function loginAction()
    {
        if ($this->getFactory()->getCustomerClient()->isLoggedIn()) {
            $this->addErrorMessage(static::GLOSSARY_KEY_CUSTOMER_ALREADY_LOGGED_IN);

            return $this->redirectResponseInternal(static::ROUTE_CUSTOMER_OVERVIEW);
        }

        if ($this->getFactory()->getAgentClient()->isLoggedIn()) {
            return $this->redirectResponseInternal(static::ROUTE_AGENT_OVERVIEW);
        }

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
