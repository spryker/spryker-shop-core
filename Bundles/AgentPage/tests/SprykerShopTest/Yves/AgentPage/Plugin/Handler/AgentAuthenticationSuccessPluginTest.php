<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\AgentPage\Plugin\Handler;

use Generated\Shared\Transfer\UserTransfer;
use SprykerShop\Yves\AgentPage\Plugin\Handler\AgentAuthenticationSuccessHandler;
use SprykerShop\Yves\AgentPage\Security\Agent;
use SprykerShopTest\Yves\AgentPage\Plugin\AbstractPluginTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;
use Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken;

/**
 * @group SprykerShop
 * @group Yves
 * @group AgentPage
 * @group Plugin
 * @group Handler
 * @group AgentAuthenticationSuccessHandlerTest
 */
class AgentAuthenticationSuccessPluginTest extends AbstractPluginTest
{
    /**
     * @return void
     */
    public function testOnAuthenticationSuccessAddsAgentSuccessfulLoginAuditLogOnSuccessfulLogin(): void
    {
        // Arrange
        $tokenMock = $this->getPostAuthenticationTokenMock();
        $request = new Request();
        $request->setSession(new Session(new MockArraySessionStorage()));
        $agentAuthenticationSuccessHandler = $this->getAgentAuthenticationSuccessHandler('Successful Login (Agent)');

        // Act
        $agentAuthenticationSuccessHandler->onAuthenticationSuccess($request, $tokenMock);
    }

    /**
     * @param string $expectedAuditLogMessage
     *
     * @return \SprykerShop\Yves\AgentPage\Plugin\Handler\AgentAuthenticationSuccessHandler
     */
    protected function getAgentAuthenticationSuccessHandler(string $expectedAuditLogMessage): AgentAuthenticationSuccessHandler
    {
        $agentAuthenticationSuccessHandler = new AgentAuthenticationSuccessHandler('/test');
        $agentAuthenticationSuccessHandler->setFactory($this->getAgentPageFactoryMock($expectedAuditLogMessage));

        return $agentAuthenticationSuccessHandler;
    }

    /**
     * @return \Symfony\Component\Security\Http\Authenticator\Token\PostAuthenticationToken|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getPostAuthenticationTokenMock(): PostAuthenticationToken
    {
        $postAuthenticationTokenMock = $this->getMockBuilder(PostAuthenticationToken::class)
            ->disableOriginalConstructor()
            ->getMock();
        $postAuthenticationTokenMock->method('getUser')->willReturn(new Agent(new UserTransfer()));

        return $postAuthenticationTokenMock;
    }
}
