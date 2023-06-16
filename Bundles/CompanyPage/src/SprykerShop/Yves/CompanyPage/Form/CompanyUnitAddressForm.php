<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageConfig getConfig()
 */
class CompanyUnitAddressForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_ID_COMPANY_UNIT_ADDRESS = 'id_company_unit_address';

    /**
     * @var string
     */
    public const FIELD_FK_COMPANY = 'fk_company';

    /**
     * @var string
     */
    public const FIELD_FK_COMPANY_BUSINESS_UNIT = 'fk_company_business_unit';

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
    public const FIELD_ADDRESS_3 = 'address3';

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
    public const FIELD_PHONE = 'phone';

    /**
     * @var string
     */
    public const OPTION_COUNTRY_CHOICES = 'country_choices';

    /**
     * @var string
     */
    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';

    /**
     * @var string
     */
    protected const VALIDATION_MIN_LENGTH_MESSAGE = 'validation.min_length';

    /**
     * @var string
     */
    protected const VALIDATION_ADDRESS_NUMBER_MESSAGE = 'validation.address_number';

    /**
     * @var string
     */
    protected const VALIDATION_ZIP_CODE_LENGTH_MESSAGE = 'validation.max_length.plural';

    /**
     * @var string
     */
    protected const VALIDATION_ZIP_CODE_MESSAGE = 'validation.zip_code';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'CompanyUnitAddressForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::OPTION_COUNTRY_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdCompanyUnitAddressField($builder, $options)
            ->addFkCompanyField($builder, $options)
            ->addFkCompanyBusinessUnitField($builder, $options)
            ->addAddress1Field($builder, $options)
            ->addAddress2Field($builder, $options)
            ->addAddress3Field($builder, $options)
            ->addZipCodeField($builder, $options)
            ->addCityField($builder, $options)
            ->addIso2CodeField($builder, $options)
            ->addPhoneField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addFkCompanyField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FK_COMPANY, HiddenType::class, [
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIdCompanyUnitAddressField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ID_COMPANY_UNIT_ADDRESS, HiddenType::class, [
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addFkCompanyBusinessUnitField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FK_COMPANY_BUSINESS_UNIT, HiddenType::class, [
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAddress1Field(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ADDRESS_1, TextType::class, [
            'label' => 'company.account.address.address1',
            'required' => true,
            'sanitize_xss' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createMinLengthConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAddress2Field(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ADDRESS_2, TextType::class, [
            'label' => 'company.account.address.number',
            'required' => true,
            'sanitize_xss' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createAddressNumberConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addAddress3Field(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ADDRESS_3, TextType::class, [
            'label' => 'company.account.address.address3',
            'required' => false,
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addZipCodeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ZIP_CODE, TextType::class, [
            'label' => 'company.account.address.zip_code',
            'required' => true,
            'sanitize_xss' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createZipCodeLengthConstraint($options),
                $this->createZipCodeConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addCityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_CITY, TextType::class, [
            'label' => 'company.account.address.city',
            'required' => true,
            'sanitize_xss' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createMinLengthConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addIso2CodeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_ISO_2_CODE, ChoiceType::class, [
            'label' => 'company.account.address.country',
            'required' => true,
            'choices' => array_flip($options[static::OPTION_COUNTRY_CHOICES]),
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return $this
     */
    protected function addPhoneField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_PHONE, TelType::class, [
            'label' => 'company.account.address.phone',
            'required' => false,
            'sanitize_xss' => true,
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint()
    {
        return new NotBlank(['message' => static::VALIDATION_NOT_BLANK_MESSAGE]);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Validator\Constraints\Length
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
     *
     * @return \Symfony\Component\Validator\Constraints\Length
     */
    protected function createMinLengthConstraint(array $options)
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
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    protected function createZipCodeConstraint(array $options): Regex
    {
        return new Regex([
            'pattern' => $this->getConfig()->getZipCodeConstraintPattern(),
            'message' => static::VALIDATION_ZIP_CODE_MESSAGE,
            'groups' => $this->getValidationGroup($options),
        ]);
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
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
     *
     * @return string
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
