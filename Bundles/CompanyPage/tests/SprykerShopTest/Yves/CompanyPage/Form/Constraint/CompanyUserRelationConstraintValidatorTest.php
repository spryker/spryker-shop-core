<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShopTest\Yves\CompanyPage\Form\Constraint;

use Generated\Shared\Transfer\CompanyUserTransfer;
use Generated\Shared\Transfer\CustomerTransfer;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface;
use SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToLocaleClientInterface;
use SprykerShop\Yves\CompanyPage\Form\Constraint\CompanyUserRelationConstraint;
use SprykerShop\Yves\CompanyPage\Form\Constraint\CompanyUserRelationConstraintValidator;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

/**
 * @group SprykerShopTest
 * @group Yves
 * @group CompanyPage
 * @group Form
 * @group Constraint
 * @group CompanyUserRelationConstraintValidatorTest
 */
class CompanyUserRelationConstraintValidatorTest extends ConstraintValidatorTestCase
{
    /**
     * @uses \SprykerShop\Yves\CompanyPage\Form\Constraint\CompanyUserRelationConstraint::GLOSSARY_KEY_COMPANY_USER_INVALID_COMPANY'
     *
     * @var string
     */
    protected const GLOSSARY_KEY_COMPANY_USER_INVALID_COMPANY = 'company_page.validation.error.company_user.unauthorized_request';

    /**
     * @uses \SprykerShop\Yves\CompanyPage\Form\Constraint\CompanyUserRelationConstraint::GLOSSARY_KEY_GLOBAL_PERMISSION_FAILED'
     *
     * @var string
     */
    protected const GLOSSARY_KEY_GLOBAL_PERMISSION_FAILED = 'global.permission.failed';

    /**
     * @var string
     */
    protected const VALIDATION_MESSAGE = 'Unauthorized request.';

    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var int
     */
    protected const TEST_ID_COMPANY = 1;

    /**
     * @var int
     */
    protected const TEST_ID_COMPANY_2 = 2;

    /**
     * @return void
     */
    public function testValidateShouldThrowAnExceptionWhenIncorrectConstraintProvided(): void
    {
        // Assert
        $this->expectException(UnexpectedTypeException::class);

        // Act
        $this->validator->validate(static::TEST_ID_COMPANY, new Email());
    }

    /**
     * @dataProvider getValidateDataProvider
     *
     * @param int $idCompany
     * @param string $translation
     * @param bool $expectedIsValid
     * @param string $expectedMessage
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return void
     */
    public function testValidate(
        int $idCompany,
        string $translation,
        bool $expectedIsValid,
        string $expectedMessage,
        ?CustomerTransfer $customerTransfer = null
    ): void {
        // Arrange
        $companyUserRelationConstraint = new CompanyUserRelationConstraint([
            CompanyUserRelationConstraint::OPTION_CUSTOMER_CLIENT => $this->createCustomerClientMock($customerTransfer),
            CompanyUserRelationConstraint::OPTION_GLOSSARY_STORAGE_CLIENT => $this->createGlossaryStorageClientMock($translation),
            CompanyUserRelationConstraint::OPTION_LOCALE_CLIENT => $this->createLocaleClientMock(),
        ]);

        // Act
        $this->validator->validate($idCompany, $companyUserRelationConstraint);

        // Assert
        $this->assertValidation($expectedIsValid, $expectedMessage);
    }

    /**
     * @return \Symfony\Component\Validator\ConstraintValidator
     */
    protected function createValidator(): ConstraintValidator
    {
        return new CompanyUserRelationConstraintValidator();
    }

    /**
     * @param bool $expectedIsValid
     * @param string $expectedMessage
     *
     * @return void
     */
    protected function assertValidation(bool $expectedIsValid, string $expectedMessage): void
    {
        if ($expectedIsValid) {
            $this->assertNoViolation();

            return;
        }

        $this->buildViolation($expectedMessage)->assertRaised();
    }

    /**
     * @return array<string, list<mixed>>
     */
    protected function getValidateDataProvider(): array
    {
        return [
            'Should return false when a customer is not set' => [
                static::TEST_ID_COMPANY, static::VALIDATION_MESSAGE, false, static::VALIDATION_MESSAGE, null,
            ],
            'Should return false when a customer does not have company user' => [
                static::TEST_ID_COMPANY, static::VALIDATION_MESSAGE, false, static::VALIDATION_MESSAGE, new CustomerTransfer(),
            ],
            'Should return false when a company user does not have company' => [
                static::TEST_ID_COMPANY,
                static::VALIDATION_MESSAGE,
                false,
                static::VALIDATION_MESSAGE,
                (new CustomerTransfer())->setCompanyUserTransfer(new CompanyUserTransfer()),
            ],
            'Should return false when a company user have another company' => [
                static::TEST_ID_COMPANY,
                static::VALIDATION_MESSAGE,
                false,
                static::VALIDATION_MESSAGE,
                (new CustomerTransfer())->setCompanyUserTransfer((new CompanyUserTransfer())->setFkCompany(static::TEST_ID_COMPANY_2)),
            ],
            'Should return true when a company user have the same company' => [
                static::TEST_ID_COMPANY,
                static::VALIDATION_MESSAGE,
                true,
                static::VALIDATION_MESSAGE,
                (new CustomerTransfer())->setCompanyUserTransfer((new CompanyUserTransfer())->setFkCompany(static::TEST_ID_COMPANY)),
            ],
            'Should return fallback glossary when translation for new glossary is not provided' => [
                static::TEST_ID_COMPANY,
                static::GLOSSARY_KEY_COMPANY_USER_INVALID_COMPANY,
                true,
                static::GLOSSARY_KEY_GLOBAL_PERMISSION_FAILED,
                (new CustomerTransfer())->setCompanyUserTransfer((new CompanyUserTransfer())->setFkCompany(static::TEST_ID_COMPANY)),
            ],
        ];
    }

    /**
     * @param \Generated\Shared\Transfer\CustomerTransfer|null $customerTransfer
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToCustomerClientInterface
     */
    protected function createCustomerClientMock(?CustomerTransfer $customerTransfer = null): CompanyPageToCustomerClientInterface
    {
        $customerClientMock = $this->getMockBuilder(CompanyPageToCustomerClientInterface::class)
            ->getMock();

        $customerClientMock->method('getCustomer')->willReturn($customerTransfer);

        return $customerClientMock;
    }

    /**
     * @param string $translation
     *
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToGlossaryStorageClientInterface
     */
    protected function createGlossaryStorageClientMock(string $translation): CompanyPageToGlossaryStorageClientInterface
    {
        $glossaryStorageClientMock = $this->getMockBuilder(CompanyPageToGlossaryStorageClientInterface::class)
            ->getMock();

        $glossaryStorageClientMock->method('translate')->willReturn($translation);

        return $glossaryStorageClientMock;
    }

    /**
     * @return \PHPUnit\Framework\MockObject\MockObject|\SprykerShop\Yves\CompanyPage\Dependency\Client\CompanyPageToLocaleClientInterface
     */
    protected function createLocaleClientMock(): CompanyPageToLocaleClientInterface
    {
        $localeClient = $this->getMockBuilder(CompanyPageToLocaleClientInterface::class)
            ->getMock();

        $localeClient->method('getCurrentLocale')->willReturn(static::LOCALE_DE);

        return $localeClient;
    }
}
