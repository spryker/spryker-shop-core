<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\AgentPage\Plugin\Handler;

use SprykerShop\Yves\AgentPage\Plugin\Handler\AgentAuthenticationFailureHandler;
use SprykerShopTest\Yves\AgentPage\Plugin\AbstractPluginTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * @group SprykerShop
 * @group Yves
 * @group AgentPage
 * @group Plugin
 * @group Handler
 * @group AgentAuthenticationFailureHandlerPluginTest
 */
class AgentAuthenticationFailurePluginTest extends AbstractPluginTest
{
    /**
     * @return void
     */
    public function testOnAuthenticationFailureAddsAgentFailedLoginAuditLogWhenLoginAttemptFails(): void
    {
        // Arrange
        $agentAuthenticationFailureHandler = $this->getAgentAuthenticationFailureHandler('Failed Login (Agent)');

        // Act
        $agentAuthenticationFailureHandler->onAuthenticationFailure(new Request(), new AuthenticationException());
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \SprykerShop\Yves\AgentPage\Plugin\Handler\AgentAuthenticationFailureHandler
     */
    protected function getAgentAuthenticationFailureHandler(string $expectedAuditLogMessage): AgentAuthenticationFailureHandler
    {
        $agentAuthenticationFailureHandler = new AgentAuthenticationFailureHandler('/test');
        $agentAuthenticationFailureHandler->setFactory($this->getAgentPageFactoryMock($expectedAuditLogMessage));

        return $agentAuthenticationFailureHandler;
    }
}
