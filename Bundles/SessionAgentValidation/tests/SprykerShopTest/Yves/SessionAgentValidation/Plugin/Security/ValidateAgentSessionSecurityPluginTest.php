<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionAgentValidation\Plugin\Security;

use Codeception\Test\Unit;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use SprykerShop\Yves\SessionAgentValidation\Plugin\Security\ValidateAgentSessionSecurityPlugin;
use SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationDependencyProvider;
use SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SessionAgentValidation
 * @group Plugin
 * @group Security
 * @group ValidateAgentSessionSecurityPluginTest
 * Add your own group annotations below this line
 */
class ValidateAgentSessionSecurityPluginTest extends Unit
{
    /**
     * @uses \SprykerShop\Yves\SessionAgentValidation\Extender\SessionAgentValidationSecurityExtender::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'secured';

    /**
     * @uses \SprykerShop\Yves\SessionAgentValidation\Extender\SessionAgentValidationSecurityExtender::SECURITY_AGENT_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_AGENT_FIREWALL_NAME = 'agent';

    /**
     * @uses \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig::AUTHENTICATION_LISTENER_FACTORY_TYPE
     *
     * @var string
     */
    protected const AUTHENTICATION_LISTENER_FACTORY_TYPE = 'agent_session_validator';

    /**
     * @uses \SprykerShop\Yves\SessionAgentValidation\Extender\SessionAgentValidationSecurityExtender::SECURITY_AGENT_SESSION_VALIDATOR
     *
     * @var string
     */
    protected const SECURITY_AGENT_SESSION_VALIDATOR = 'security.authentication_listener.agent_session_validator';

    /**
     * @uses \SprykerShop\Yves\SessionAgentValidation\Extender\SessionAgentValidationSecurityExtender::FIREWALL_KEY_CONTEXT
     *
     * @var string
     */
    protected const FIREWALL_KEY_CONTEXT = 'context';

    /**
     * @uses \SprykerShop\Yves\SessionAgentValidation\Extender\SessionAgentValidationSecurityExtender::SECURITY_FACTORY_AGENT_SESSION_VALIDATOR
     *
     * @var string
     */
    protected const SECURITY_FACTORY_AGENT_SESSION_VALIDATOR = 'security.authentication_listener.factory.agent_session_validator';

    /**
     * @uses \SprykerShop\Yves\SessionAgentValidation\Extender\SessionAgentValidationSecurityExtender::SERVICE_SECURITY_AUTHENTICATION_LISTENER_AGENT_SESSION_VALIDATOR_PROTO
     *
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_AGENT_SESSION_VALIDATOR_PROTO = 'security.authentication_listener.agent_session_validator._proto';

    /**
     * @var \SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester
     */
    protected SessionAgentValidationYvesTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            SessionAgentValidationDependencyProvider::PLUGIN_SESSION_AGENT_VALIDATOR,
            $this->tester->createSessionAgentValidatorPluginMock(),
        );
    }

    /**
     * @return void
     */
    public function testExtendShouldNotExtendFirewallWhenFirewallDoesNotExist(): void
    {
        // Arrange
        $validateAgentSessionSecurityPlugin = new ValidateAgentSessionSecurityPlugin();

        // Act
        $securityBuilder = $validateAgentSessionSecurityPlugin->extend(new SecurityConfiguration(), $this->tester->getContainer());

        // Assert
        $this->assertCount(0, $securityBuilder->getConfiguration()->getFirewalls());
    }

    /**
     * @return void
     */
    public function testExtendShouldExtendFirewallsWhenFirewallsExist(): void
    {
        // Arrange
        $validateAgentSessionSecurityPlugin = new ValidateAgentSessionSecurityPlugin();

        $securityBuilder = (new SecurityConfiguration())
            ->addFirewall(static::SECURITY_FIREWALL_NAME, [])
            ->addFirewall(static::SECURITY_AGENT_FIREWALL_NAME, []);

        // Act
        $securityBuilder = $validateAgentSessionSecurityPlugin->extend($securityBuilder, $this->tester->getContainer());

        // Assert
        $firewalls = $securityBuilder->getConfiguration()->getFirewalls();
        $this->assertCount(2, $firewalls);

        /** @var array<string, mixed> $securityFirewall */
        $securityFirewall = $firewalls[static::SECURITY_FIREWALL_NAME];
        $this->assertArrayHasKey(static::AUTHENTICATION_LISTENER_FACTORY_TYPE, $securityFirewall);
        $this->assertSame(static::SECURITY_AGENT_SESSION_VALIDATOR, $securityFirewall[static::AUTHENTICATION_LISTENER_FACTORY_TYPE]);
        $this->assertArrayNotHasKey(static::FIREWALL_KEY_CONTEXT, $securityFirewall);

        /** @var array<string, mixed> $securityAgentFirewall */
        $securityAgentFirewall = $firewalls[static::SECURITY_AGENT_FIREWALL_NAME];
        $this->assertArrayHasKey(static::AUTHENTICATION_LISTENER_FACTORY_TYPE, $securityAgentFirewall);
        $this->assertSame(static::SECURITY_AGENT_SESSION_VALIDATOR, $securityAgentFirewall[static::AUTHENTICATION_LISTENER_FACTORY_TYPE]);
        $this->assertArrayNotHasKey(static::FIREWALL_KEY_CONTEXT, $securityAgentFirewall);
    }

    /**
     * @return void
     */
    public function testExtendShouldExtendSecuredFirewallWithContext(): void
    {
        // Arrange
        $validateAgentSessionSecurityPlugin = new ValidateAgentSessionSecurityPlugin();

        $securityBuilder = (new SecurityConfiguration())->addFirewall(static::SECURITY_FIREWALL_NAME, [
            static::FIREWALL_KEY_CONTEXT => static::FIREWALL_KEY_CONTEXT,
        ]);

        // Act
        $securityBuilder = $validateAgentSessionSecurityPlugin->extend($securityBuilder, $this->tester->getContainer());

        // Assert
        $firewalls = $securityBuilder->getConfiguration()->getFirewalls();
        $this->assertCount(1, $firewalls);

        /** @var array<string, mixed> $firewall */
        $firewall = $firewalls[static::SECURITY_FIREWALL_NAME];
        $this->assertArrayHasKey(static::FIREWALL_KEY_CONTEXT, $firewall);
    }

    /**
     * @return void
     */
    public function testExtendShouldExtendContainerWithAuthenticationListenerFactory(): void
    {
        // Arrange
        $validateAgentSessionSecurityPlugin = new ValidateAgentSessionSecurityPlugin();
        $container = $this->tester->getContainer();

        // Act
        $validateAgentSessionSecurityPlugin->extend(new SecurityConfiguration(), $container);

        // Assert
        $this->assertTrue($container->has(static::SECURITY_FACTORY_AGENT_SESSION_VALIDATOR));
    }

    /**
     * @return void
     */
    public function testExtendShouldExtendContainerWithAuthenticationListenerPrototype(): void
    {
        // Arrange
        $validateAgentSessionSecurityPlugin = new ValidateAgentSessionSecurityPlugin();
        $container = $this->tester->getContainer();

        // Act
        $validateAgentSessionSecurityPlugin->extend(new SecurityConfiguration(), $container);

        // Assert
        $this->assertTrue($container->has(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_AGENT_SESSION_VALIDATOR_PROTO));
    }
}
