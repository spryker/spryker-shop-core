<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionCustomerValidationPage\Plugin\Security;

use Codeception\Test\Unit;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use SprykerShop\Yves\SessionCustomerValidationPage\Plugin\Security\ValidateCustomerSessionSecurityPlugin;
use SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageDependencyProvider;
use SprykerShopTest\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageYvesTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SessionCustomerValidationPage
 * @group Plugin
 * @group Security
 * @group ValidateCustomerSessionSecurityPluginTest
 * Add your own group annotations below this line
 */
class ValidateCustomerSessionSecurityPluginTest extends Unit
{
    /**
     * @uses \SprykerShop\Yves\SessionCustomerValidationPage\Extender\SessionCustomerValidationSecurityExtender::SECURITY_FIREWALL_NAME
     *
     * @var string
     */
    protected const SECURITY_FIREWALL_NAME = 'secured';

    /**
     * @uses \SprykerShop\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageConfig::AUTHENTICATION_LISTENER_FACTORY_TYPE
     *
     * @var string
     */
    protected const AUTHENTICATION_LISTENER_FACTORY_TYPE = 'customer_session_validator';

    /**
     * @uses \SprykerShop\Yves\SessionCustomerValidationPage\Extender\SessionCustomerValidationSecurityExtender::SECURITY_CUSTOMER_SESSION_VALIDATOR
     *
     * @var string
     */
    protected const SECURITY_CUSTOMER_SESSION_VALIDATOR = 'security.authentication_listener.customer_session_validator';

    /**
     * @uses \SprykerShop\Yves\SessionCustomerValidationPage\Extender\SessionCustomerValidationSecurityExtender::FIREWALL_KEY_CONTEXT
     *
     * @var string
     */
    protected const FIREWALL_KEY_CONTEXT = 'context';

    /**
     * @uses \SprykerShop\Yves\SessionCustomerValidationPage\Extender\SessionCustomerValidationSecurityExtender::SECURITY_FACTORY_CUSTOMER_SESSION_VALIDATOR
     *
     * @var string
     */
    protected const SECURITY_FACTORY_CUSTOMER_SESSION_VALIDATOR = 'security.authentication_listener.factory.customer_session_validator';

    /**
     * @uses \SprykerShop\Yves\SessionCustomerValidationPage\Extender\SessionCustomerValidationSecurityExtender::SERVICE_SECURITY_AUTHENTICATION_LISTENER_CUSTOMER_SESSION_VALIDATOR_PROTO
     *
     * @var string
     */
    protected const SERVICE_SECURITY_AUTHENTICATION_LISTENER_CUSTOMER_SESSION_VALIDATOR_PROTO = 'security.authentication_listener.customer_session_validator._proto';

    /**
     * @var \SprykerShopTest\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageYvesTester
     */
    protected SessionCustomerValidationPageYvesTester $tester;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->tester->setDependency(
            SessionCustomerValidationPageDependencyProvider::PLUGIN_CUSTOMER_SESSION_VALIDATOR,
            $this->tester->createCustomerSessionValidatorPluginMock(),
        );
    }

    /**
     * @return void
     */
    public function testExtendShouldNotExtendFirewallWhenFirewallDoesNotExist(): void
    {
        // Arrange
        $validateCustomerSessionSecurityPlugin = new ValidateCustomerSessionSecurityPlugin();

        // Act
        $securityBuilder = $validateCustomerSessionSecurityPlugin->extend(new SecurityConfiguration(), $this->tester->getContainer());

        // Assert
        $this->assertCount(0, $securityBuilder->getConfiguration()->getFirewalls());
    }

    /**
     * @return void
     */
    public function testExtendShouldExtendFirewallsWhenFirewallsExist(): void
    {
        // Arrange
        $validateCustomerSessionSecurityPlugin = new ValidateCustomerSessionSecurityPlugin();

        $securityBuilder = (new SecurityConfiguration())
            ->addFirewall(static::SECURITY_FIREWALL_NAME, [
                static::FIREWALL_KEY_CONTEXT => static::FIREWALL_KEY_CONTEXT,
            ]);

        // Act
        $securityBuilder = $validateCustomerSessionSecurityPlugin->extend($securityBuilder, $this->tester->getContainer());

        // Assert
        $firewalls = $securityBuilder->getConfiguration()->getFirewalls();
        $this->assertCount(1, $firewalls);

        /** @var array<string, mixed> $securityFirewall */
        $securityFirewall = $firewalls[static::SECURITY_FIREWALL_NAME];
        $this->assertArrayHasKey(static::AUTHENTICATION_LISTENER_FACTORY_TYPE, $securityFirewall);
        $this->assertSame(static::SECURITY_CUSTOMER_SESSION_VALIDATOR, $securityFirewall[static::AUTHENTICATION_LISTENER_FACTORY_TYPE]);
        $this->assertArrayHasKey(static::FIREWALL_KEY_CONTEXT, $securityFirewall);
    }

    /**
     * @return void
     */
    public function testExtendShouldExtendContainerWithAuthenticationListenerFactory(): void
    {
        // Arrange
        $validateCustomerSessionSecurityPlugin = new ValidateCustomerSessionSecurityPlugin();
        $container = $this->tester->getContainer();

        // Act
        $validateCustomerSessionSecurityPlugin->extend(new SecurityConfiguration(), $container);

        // Assert
        $this->assertTrue($container->has(static::SECURITY_FACTORY_CUSTOMER_SESSION_VALIDATOR));
    }

    /**
     * @return void
     */
    public function testExtendShouldExtendContainerWithAuthenticationListenerPrototype(): void
    {
        // Arrange
        $validateCustomerSessionSecurityPlugin = new ValidateCustomerSessionSecurityPlugin();
        $container = $this->tester->getContainer();

        // Act
        $validateCustomerSessionSecurityPlugin->extend(new SecurityConfiguration(), $container);

        // Assert
        $this->assertTrue($container->has(static::SERVICE_SECURITY_AUTHENTICATION_LISTENER_CUSTOMER_SESSION_VALIDATOR_PROTO));
    }
}
