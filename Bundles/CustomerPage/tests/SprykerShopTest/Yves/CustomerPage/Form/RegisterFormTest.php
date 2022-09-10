<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CustomerPage\Form;

use SprykerShop\Yves\CustomerPage\Form\RegisterForm;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CustomerPage
 * @group Form
 * @group RegisterFormTest
 */
class RegisterFormTest extends TypeTestCase
{
    /**
     * @return void
     */
    public function testRegisterFormIsValid(): void
    {
        // Arrange
        $registerForm = $this->factory->create(RegisterForm::class);
        $data = $this->getCorrectTestData();

        // Act
        $registerForm->submit($data);

        // Assert
        $this->assertTrue($registerForm->isSynchronized());
        $this->assertTrue($this->isFormValid($registerForm));
    }

    /**
     * @return void
     */
    public function testFirstNameIsNotValid(): void
    {
        // Arrange
        $registerForm = $this->factory->create(RegisterForm::class);
        $data = $this->getCorrectTestData();
        $data[RegisterForm::FIELD_FIRST_NAME] = 'https://dummysite.org/';

        // Act
        $registerForm->submit($data);

        // Assert
        $this->assertTrue($registerForm->isSynchronized());
        $this->assertFalse($this->isFormValid($registerForm));
    }

    /**
     * @return void
     */
    public function testLastNameIsNotValid(): void
    {
        // Arrange
        $registerForm = $this->factory->create(RegisterForm::class);
        $data = $this->getCorrectTestData();
        $data[RegisterForm::FIELD_LAST_NAME] = 'https://dummysite.org/';

        // Act
        $registerForm->submit($data);

        // Assert
        $this->assertTrue($registerForm->isSynchronized());
        $this->assertFalse($this->isFormValid($registerForm));
    }

    /**
     * @return list<\Symfony\Component\Form\FormExtensionInterface>
     */
    protected function getExtensions(): array
    {
        return [
            new ValidatorExtension(
                Validation::createValidator(),
            ),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getCorrectTestData(): array
    {
        return [
            RegisterForm::FIELD_SALUTATION => 'Mr',
            RegisterForm::FIELD_FIRST_NAME => 'Dummy',
            RegisterForm::FIELD_LAST_NAME => 'Dummyngo',
            RegisterForm::FIELD_EMAIL => 'dummy@dummy.com',
            RegisterForm::FIELD_PASSWORD => 'someDummyPassword',
            RegisterForm::FIELD_IS_GUEST => false,
            RegisterForm::FIELD_ACCEPT_TERMS => '1',
        ];
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return bool
     */
    protected function isFormValid(FormInterface $form): bool
    {
        foreach ($form as $element) {
            if ($element->getErrors()->count() !== 0) {
                return false;
            }
        }

        return true;
    }
}
