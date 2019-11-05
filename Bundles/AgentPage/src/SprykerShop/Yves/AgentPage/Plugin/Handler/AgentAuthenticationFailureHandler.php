<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\AgentPage\Plugin\Handler;

use Spryker\Yves\Kernel\AbstractPlugin;
use SprykerShop\Yves\AgentPage\Plugin\Provider\AgentPageControllerProvider;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationFailureHandlerInterface;

/**
 * @method \SprykerShop\Yves\AgentPage\AgentPageFactory getFactory()
 */
class AgentAuthenticationFailureHandler extends AbstractPlugin implements AuthenticationFailureHandlerInterface
{
    protected const MESSAGE_AGENT_AUTHENTICATION_FAILED = 'agent.authentication.failed';

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\Security\Core\Exception\AuthenticationException $exception
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $foo = 'bar';
        $this->getFactory()
            ->getMessengerClient()
            ->addErrorMessage(static::MESSAGE_AGENT_AUTHENTICATION_FAILED);

        return $this->getFactory()
            ->createRedirectResponse(
                $this->getRedirectUrl()
            );
    }

    /**
     * @return string
     */
    protected function getRedirectUrl(): string
    {
        return $this->getFactory()
            ->getApplication()
            ->url(AgentPageControllerProvider::ROUTE_LOGIN);
    }
}
