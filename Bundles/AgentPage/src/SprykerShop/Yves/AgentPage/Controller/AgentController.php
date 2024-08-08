<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Controller;

use Spryker\Yves\Kernel\View\View;
use SprykerShop\Yves\ShopApplication\Controller\AbstractController;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class AgentController extends AbstractController
{
    /**
     * @var string
     */
    protected const LOGIN_REDIRECT_URL = '/agent/login';

    /**
     * @return \Spryker\Yves\Kernel\View\View
     */
    public function indexAction(): View
    {
        $viewData = $this->executeIndexAction();

        return $this->view($viewData, [], $this->getTemplatePath());
    }

    /**
     * @return array<string, mixed>
     */
    protected function executeIndexAction(): array
    {
        return [
            'agent' => $this->getFactory()->getAgentClient()->isLoggedIn() ? $this->getFactory()->getAgentClient()->getAgent() : null,
            'loginRedirectUrl' => static::LOGIN_REDIRECT_URL,
        ];
    }

    /**
     * @return string
     */
    protected function getTemplatePath(): string
    {
        if ($this->getFactory()->getAgentClient()->isLoggedIn() === false) {
            return '@AgentPage/views/login/redirect-to-login.twig';
        }

        return '@AgentPage/views/overview/overview.twig';
    }
}
