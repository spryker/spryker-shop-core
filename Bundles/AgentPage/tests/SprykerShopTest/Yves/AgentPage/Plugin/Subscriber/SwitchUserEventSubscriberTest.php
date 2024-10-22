<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\AgentPage\Plugin\Subscriber;

use Generated\Shared\Transfer\CustomerTransfer;
use Generated\Shared\Transfer\UserTransfer;
use SprykerShop\Yves\AgentPage\Plugin\Subscriber\SwitchUserEventSubscriber;
use SprykerShop\Yves\AgentPage\Security\Agent;
use SprykerShop\Yves\CustomerPage\Security\Customer;
use SprykerShopTest\Yves\AgentPage\Plugin\AbstractPluginTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Event\SwitchUserEvent;

/**
 * @group SprykerShop
 * @group Yves
 * @group AgentPage
 * @group Plugin
 * @group Subscriber
 * @group SwitchUserEventSubscriberTest
 */
class SwitchUserEventSubscriberTest extends AbstractPluginTest
{
    /**
     * @return void
     */
    public function testSwitchUserAddsImpersonationStartedAuditLogWhenImpersonationStarted(): void
    {
        // Arrange
        $switchUserEventSubscriber = $this->getSwitchUserEventSubscriber('Impersonation Started');
        $switchUserEvent = new SwitchUserEvent(
            new Request(),
            new Customer(new CustomerTransfer(), 'test', 'test'),
            $this->getSwitchUserTokenMock(),
        );

        // Act
        $switchUserEventSubscriber->switchUser($switchUserEvent);
    }

    /**
     * @return void
     */
    public function testSwitchUserAddsImpersonationEndedAuditLogWhenImpersonationEnded(): void
    {
        // Arrange
        $switchUserEventSubscriber = $this->getSwitchUserEventSubscriber('Impersonation Ended');
        $switchUserEvent = new SwitchUserEvent(new Request(), new Agent(new UserTransfer()), $this->getSwitchUserTokenMock());

        // Act
        $switchUserEventSubscriber->switchUser($switchUserEvent);
    }

    /**
     * @param string $expectedMessage
     *
     * @return \SprykerShop\Yves\AgentPage\Plugin\Subscriber\SwitchUserEventSubscriber|\PHPUnit\Framework\MockObject\MockObject
     */
    protected function getSwitchUserEventSubscriber(string $expectedMessage): SwitchUserEventSubscriber
    {
        $agentPageFactoryMock = $this->getAgentPageFactoryMock($expectedMessage);
        $switchUserEventSubscriber = new SwitchUserEventSubscriber();
        $switchUserEventSubscriber->setFactory($agentPageFactoryMock);

        return $switchUserEventSubscriber;
    }
}
