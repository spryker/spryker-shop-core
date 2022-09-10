<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CompanyPage\Form;

use SprykerShop\Yves\CompanyPage\Form\CompanyRegisterForm;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CompanyPage
 * @group Form
 * @group CompanyCompanyRegisterFormTest
 */
class CompanyRegisterFormTest extends TypeTestCase
{
    /**
     * @return void
     */
    public function testCompanyUserFormIsValid(): void
    {
        // Arrange
        $registerForm = $this->factory->create(CompanyRegisterForm::class);
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
        $registerForm = $this->factory->create(CompanyRegisterForm::class);
        $data = $this->getCorrectTestData();
        $data[CompanyRegisterForm::FIELD_FIRST_NAME] = 'https://dummysite.org/';

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
        $registerForm = $this->factory->create(CompanyRegisterForm::class);
        $data = $this->getCorrectTestData();
        $data[CompanyRegisterForm::FIELD_LAST_NAME] = 'https://dummysite.org/';

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
            CompanyRegisterForm::FIELD_SALUTATION => 'Mr',
            CompanyRegisterForm::FIELD_FIRST_NAME => 'Dummy',
            CompanyRegisterForm::FIELD_LAST_NAME => 'Dummyngo',
            CompanyRegisterForm::FIELD_COMPANY_NAME => 'DummyInc.',
            CompanyRegisterForm::FIELD_EMAIL => 'dummy@dummy.com',
            CompanyRegisterForm::FIELD_PASSWORD => 'someDummyPassword',
            CompanyRegisterForm::FIELD_IS_GUEST => false,
            CompanyRegisterForm::FIELD_ACCEPT_TERMS => '1',
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
