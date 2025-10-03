<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\MerchantRegistrationRequestPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class MerchantRegistrationRequestPageForm extends AbstractType
{
    /**
     * @var string
     */
    public const BLOCK_PREFIX = 'MerchantRegistrationRequestForm';

    /**
     * @var string
     */
    public const FIELD_ADDRESS_1 = 'address1';

    /**
     * @var string
     */
    public const FIELD_ADDRESS_2 = 'address2';

    /**
     * @var string
     */
    public const FIELD_ZIP_CODE = 'zip_code';

    /**
     * @var string
     */
    public const FIELD_CITY = 'city';

    /**
     * @var string
     */
    public const FIELD_ISO_2_CODE = 'iso2_code';

    /**
     * @var string
     */
    public const FIELD_REGISTRATION_NUMBER = 'registration_number';

    /**
     * @var string
     */
    public const FIELD_CONTACT_PERSON_TITLE = 'contact_person_title';

    /**
     * @var string
     */
    public const FIELD_CONTACT_PERSON_FIRST_NAME = 'contact_person_first_name';

    /**
     * @var string
     */
    public const FIELD_CONTACT_PERSON_LAST_NAME = 'contact_person_last_name';

    /**
     * @var string
     */
    public const FIELD_CONTACT_PERSON_ROLE = 'contact_person_role';

    /**
     * @var string
     */
    public const FIELD_CONTACT_PERSON_PHONE = 'contact_person_phone';

    /**
     * @var string
     */
    public const FIELD_EMAIL = 'email';

    /**
     * @var string
     */
    public const FIELD_ACCEPT_TERMS = 'accept_terms';

    /**
     * @var string
     */
    public const OPTION_COUNTRY_CHOICES = 'country_choices';

    /**
     * @var string
     */
    public const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';

    /**
     * @var string
     */
    public const VALIDATION_MIN_LENGTH_MESSAGE = 'validation.min_length';

    /**
     * @var string
     */
    public const VALIDATION_ADDRESS_NUMBER_MESSAGE = 'validation.address_number';

    /**
     * @var string
     */
    public const VALIDATION_ZIP_CODE_LENGTH_MESSAGE = 'validation.max_length.plural';

    /**
     * @var string
     */
    public const VALIDATION_ZIP_CODE_MESSAGE = 'validation.zip_code';

    /**
     * @var array<string, string>
     */
    protected const SALUTATION_CHOICES = [
        'Ms' => 'Ms',
        'Mr' => 'Mr',
        'Mrs' => 'Mrs',
        'Dr' => 'Dr',
    ];

    /**
     * @var string
     */
    public const FIELD_COMPANY_NAME = 'company_name';

    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_COUNTRY_CHOICES);
    }

    /**
     * @param array<string, mixed> $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addAddress1Field($builder, $options)
            ->addAddress2Field($builder, $options)
            ->addZipCodeField($builder, $options)
            ->addCityField($builder, $options)
            ->addIso2CodeField($builder, $options)
            ->addCompanyNameField($builder)
            ->addRegistrationNumberField($builder)
            ->addContactPersonTitleField($builder)
            ->addContactPersonFirstNameField($builder)
            ->addContactPersonLastNameField($builder)
            ->addEmailField($builder)
            ->addContactPersonPhoneField($builder)
            ->addContactPersonRoleField($builder)
            ->addAcceptTermsField($builder);
    }

    /**
     * @return $this
     */
    protected function addAcceptTermsField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ACCEPT_TERMS, CheckboxType::class, [
            'label' => 'merchant_registration_request_page.accept_terms',
            'mapped' => false,
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addContactPersonRoleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONTACT_PERSON_ROLE, TextType::class, [
            'label' => 'merchant_registration_request_page.contact_person_role',
            'constraints' => $this->getRequiredTextFieldConstraints(),
            'required' => true,
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addContactPersonPhoneField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONTACT_PERSON_PHONE, TextType::class, [
            'label' => 'merchant_registration_request_page.contact_person_phone',
            'constraints' => [new Length(['max' => 255])],
            'required' => false,
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addContactPersonLastNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONTACT_PERSON_LAST_NAME, TextType::class, [
            'label' => 'merchant_registration_request_page.contact_person_last_name',
            'constraints' => $this->getRequiredTextFieldConstraints(),
            'required' => true,
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_EMAIL, EmailType::class, [
            'label' => 'merchant_registration_request_page.email',
            'required' => true,
            'constraints' => $this->getEmailFieldConstraints(),
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getEmailFieldConstraints(): array
    {
        return [
            new NotBlank(),
            new Email(),
            new Length(['max' => 255]),
        ];
    }

    /**
     * @return $this
     */
    protected function addCompanyNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_COMPANY_NAME, TextType::class, [
            'label' => 'merchant_registration_request_page.company_name',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIso2CodeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ISO_2_CODE, ChoiceType::class, [
            'label' => 'merchant_registration_request_page.country',
            'required' => true,
            'choices' => array_flip($options[static::OPTION_COUNTRY_CHOICES]),
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAddress1Field(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ADDRESS_1, TextType::class, [
            'label' => 'merchant_registration_request_page.street',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createMinLengthConstraint($options),
            ],
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAddress2Field(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ADDRESS_2, TextType::class, [
            'label' => 'merchant_registration_request_page.house_number',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createAddressNumberConstraint($options),
            ],
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addZipCodeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ZIP_CODE, TextType::class, [
            'label' => 'merchant_registration_request_page.zip_code',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createZipCodeLengthConstraint($options),
                $this->createZipCodeConstraint($options),
            ],
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_CITY, TextType::class, [
            'label' => 'merchant_registration_request_page.city',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createMinLengthConstraint($options),
            ],
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addRegistrationNumberField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_REGISTRATION_NUMBER, TextType::class, [
            'label' => 'merchant_registration_request_page.merchant_registration_number',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                new Length(['max' => 255]),
            ],
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addContactPersonTitleField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONTACT_PERSON_TITLE, ChoiceType::class, [
            'choices' => array_flip(static::SALUTATION_CHOICES),
            'label' => 'merchant_registration_request_page.contact_person_title',
            'placeholder' => 'Select one',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @return $this
     */
    protected function addContactPersonFirstNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_CONTACT_PERSON_FIRST_NAME, TextType::class, [
            'label' => 'merchant_registration_request_page.contact_person_first_name',
            'constraints' => $this->getRequiredTextFieldConstraints(),
            'required' => true,
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @return array<\Symfony\Component\Validator\Constraint>
     */
    protected function getRequiredTextFieldConstraints(): array
    {
        return [
            new NotBlank(),
            new Length(['max' => 255]),
        ];
    }

    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank(['message' => static::VALIDATION_NOT_BLANK_MESSAGE]);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function createZipCodeLengthConstraint(array $options): Length
    {
        return new Length([
            'max' => 15,
            'groups' => $this->getValidationGroup($options),
            'maxMessage' => static::VALIDATION_ZIP_CODE_LENGTH_MESSAGE,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function createMinLengthConstraint(array $options): Length
    {
        $validationGroup = $this->getValidationGroup($options);

        return new Length([
            'min' => 3,
            'groups' => $validationGroup,
            'minMessage' => static::VALIDATION_MIN_LENGTH_MESSAGE,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function createZipCodeConstraint(array $options): Regex
    {
        return new Regex([
            'pattern' => '/^\d{5}$/',
            'message' => static::VALIDATION_ZIP_CODE_MESSAGE,
            'groups' => $this->getValidationGroup($options),
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function createAddressNumberConstraint(array $options): Regex
    {
        $validationGroup = $this->getValidationGroup($options);

        return new Regex([
            'pattern' => '/^\d+[a-zA-Z]*$/',
            'message' => static::VALIDATION_ADDRESS_NUMBER_MESSAGE,
            'groups' => $validationGroup,
        ]);
    }

    /**
     * @param array<string, mixed> $options
     */
    protected function getValidationGroup(array $options): string
    {
        $validationGroup = Constraint::DEFAULT_GROUP;

        if (!empty($options['validation_group'])) {
            $validationGroup = $options['validation_group'];
        }

        return $validationGroup;
    }
}
