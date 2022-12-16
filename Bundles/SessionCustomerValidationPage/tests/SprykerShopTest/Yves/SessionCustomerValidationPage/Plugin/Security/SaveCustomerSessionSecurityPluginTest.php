<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\SessionCustomerValidationPage\Plugin\Security;

use Codeception\Test\Unit;
use Spryker\Shared\Security\Configuration\SecurityConfiguration;
use SprykerShop\Yves\SessionCustomerValidationPage\Plugin\Security\SaveCustomerSessionSecurityPlugin;
use SprykerShopTest\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageYvesTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerShopTest
 * @group Yves
 * @group SessionCustomerValidationPage
 * @group Plugin
 * @group Security
 * @group SaveCustomerSessionSecurityPluginTest
 * Add your own group annotations below this line
 */
class SaveCustomerSessionSecurityPluginTest extends Unit
{
    /**
     * @var \SprykerShopTest\Yves\SessionCustomerValidationPage\SessionCustomerValidationPageYvesTester
     */
    protected SessionCustomerValidationPageYvesTester $tester;

    /**
     * @return void
     */
    public function testExtendShouldAddEventSubscriber(): void
    {
        // Arrange
        $saveCustomerSessionSecurityPlugin = new SaveCustomerSessionSecurityPlugin();

        // Act
        $securityBuilder = $saveCustomerSessionSecurityPlugin->extend(
            new SecurityConfiguration(),
            $this->tester->getContainer(),
        );

        // Assert
        $this->assertCount(1, $securityBuilder->getConfiguration()->getEventSubscribers());
    }
}
