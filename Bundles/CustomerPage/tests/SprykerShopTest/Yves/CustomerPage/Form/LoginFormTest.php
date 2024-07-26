<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Form;

use PHPUnit\Framework\TestCase;
use SprykerShop\Yves\CustomerPage\CustomerPageConfig;
use SprykerShop\Yves\CustomerPage\Form\LoginForm;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CustomerPage
 * @group Form
 * @group LoginFormTest
 */
class LoginFormTest extends TestCase
{
    /**
     * @var \SprykerShop\Yves\CustomerPage\Form\LoginForm|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $loginForm;

    /**
     * @var \Symfony\Component\Form\FormBuilderInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $builderMock;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        $this->loginForm = $this->getMockBuilder(LoginForm::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['addRememberMeField', 'getConfig'])
            ->getMock();

        $this->builderMock = $this->getMockBuilder(FormBuilderInterface::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @return void
     */
    public function testRememberMeFieldIsAddedWhenEnabled(): void
    {
        // Arrange
        $configMock = $this->getConfigMock(true);

        // Assert
        $this->loginForm->expects($this->once())
            ->method('getConfig')
            ->willReturn($configMock);

        $this->loginForm->expects($this->once())
            ->method('addRememberMeField')
            ->with($this->builderMock);

        // Act
        $this->loginForm->buildForm($this->builderMock, []);
    }

    /**
     * @return void
     */
    public function testRememberMeFieldIsNotAddedWhenDisabled(): void
    {
        // Arrange
        $configMock = $this->getConfigMock(false);

        // Assert
        $this->loginForm->expects($this->once())
            ->method('getConfig')
            ->willReturn($configMock);

        $this->loginForm->expects($this->never())
            ->method('addRememberMeField')
            ->with($this->builderMock);

        // Act
        $this->loginForm->buildForm($this->builderMock, []);
    }

    /**
     * @param bool $isRememberMeEnabled
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CustomerPage\CustomerPageConfig
     */
    protected function getConfigMock(bool $isRememberMeEnabled = false): CustomerPageConfig
    {
        $configMock = $this->getMockBuilder(CustomerPageConfig::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['isRememberMeEnabled'])
            ->getMock();

        $configMock->method('isRememberMeEnabled')->willReturn($isRememberMeEnabled);

        return $configMock;
    }
}
