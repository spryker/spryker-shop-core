<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionAgentValidation\Plugin\Security;

use Codeception\Test\Unit;
use SprykerShop\Yves\SessionAgentValidation\Plugin\Security\SessionAgentValidationSecurityAuthenticationListenerFactoryTypeExpanderPlugin;
use SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SessionAgentValidation
 * @group Plugin
 * @group Security
 * @group SessionAgentValidationSecurityAuthenticationListenerFactoryTypeExpanderPluginTest
 * Add your own group annotations below this line
 */
class SessionAgentValidationSecurityAuthenticationListenerFactoryTypeExpanderPluginTest extends Unit
{
    /**
     * @var string
     */
    protected const TEST_SECURITY_AUTHENTICATION_LISTENER_FACTORY_TYPE = 'test_security_type';

    /**
     * @uses \SprykerShop\Yves\SessionAgentValidation\SessionAgentValidationConfig::AUTHENTICATION_LISTENER_FACTORY_TYPE
     *
     * @var string
     */
    protected const AUTHENTICATION_LISTENER_FACTORY_TYPE = 'agent_session_validator';

    /**
     * @var \SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester
     */
    protected SessionAgentValidationYvesTester $tester;

    /**
     * @return void
     */
    public function testExpandShouldAddEventSubscriber(): void
    {
        // Arrange
        $securityAuthenticationListenerFactoryTypeExpanderPlugin = new SessionAgentValidationSecurityAuthenticationListenerFactoryTypeExpanderPlugin();
        $expectedAuthenticationListenerFactoryTypes = [
            static::TEST_SECURITY_AUTHENTICATION_LISTENER_FACTORY_TYPE,
            static::AUTHENTICATION_LISTENER_FACTORY_TYPE,
        ];

        // Act
        $authenticationListenerFactoryTypes = $securityAuthenticationListenerFactoryTypeExpanderPlugin->expand([
            static::TEST_SECURITY_AUTHENTICATION_LISTENER_FACTORY_TYPE,
        ]);

        // Assert
        $this->assertSame($expectedAuthenticationListenerFactoryTypes, $authenticationListenerFactoryTypes);
    }
}
