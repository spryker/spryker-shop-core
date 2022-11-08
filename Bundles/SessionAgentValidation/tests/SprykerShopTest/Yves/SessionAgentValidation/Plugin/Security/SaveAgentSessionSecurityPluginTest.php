<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionAgentValidation\Plugin\Security;

use Codeception\Test\Unit;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use SprykerShop\Yves\SessionAgentValidation\Plugin\Security\SaveAgentSessionSecurityPlugin;
use SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SessionAgentValidation
 * @group Plugin
 * @group Security
 * @group SaveAgentSessionSecurityPluginTest
 * Add your own group annotations below this line
 */
class SaveAgentSessionSecurityPluginTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SessionAgentValidation\SessionAgentValidationYvesTester
     */
    protected SessionAgentValidationYvesTester $tester;

    /**
     * @return void
     */
    public function testExtendShouldAddEventSubscriber(): void
    {
        // Arrange
        $saveAgentSessionSecurityPlugin = new SaveAgentSessionSecurityPlugin();

        // Act
        $securityBuilder = $saveAgentSessionSecurityPlugin->extend(
            new SecurityConfiguration(),
            $this->tester->getContainer(),
        );

        // Assert
        $this->assertCount(1, $securityBuilder->getConfiguration()->getEventSubscribers());
    }
}
