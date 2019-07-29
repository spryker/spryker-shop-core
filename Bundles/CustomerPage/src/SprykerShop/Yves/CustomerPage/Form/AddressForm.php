<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CustomerPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
 * @method \SprykerShop\Yves\CustomerPage\CustomerPageConfig getConfig()
 */
class AddressForm extends AbstractType
{
    public const FIELD_SALUTATION = 'salutation';
    public const FIELD_FIRST_NAME = 'first_name';
    public const FIELD_LAST_NAME = 'last_name';
    public const FIELD_COMPANY = 'company';
    public const FIELD_ADDRESS_1 = 'address1';
    public const FIELD_ADDRESS_2 = 'address2';
    public const FIELD_ADDRESS_3 = 'address3';
    public const FIELD_ZIP_CODE = 'zip_code';
    public const FIELD_CITY = 'city';
    public const FIELD_ISO_2_CODE = 'iso2_code';
    public const FIELD_PHONE = 'phone';
    public const FIELD_IS_DEFAULT_SHIPPING = 'is_default_shipping';
    public const FIELD_IS_DEFAULT_BILLING = 'is_default_billing';
    public const FIELD_ID_CUSTOMER_ADDRESS = 'id_customer_address';

    public const OPTION_COUNTRY_CHOICES = 'country_choices';

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';
    protected const VALIDATION_ADDRESS_NUMBER_MESSAGE = 'validation.address_number';
    protected const VALIDATION_ZIP_CODE_MESSAGE = 'validation.zip_code';
    protected const VALIDATION_MIN_LENGTH_MESSAGE = 'validation.min_length';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'addressForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::OPTION_COUNTRY_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this
            ->addSalutationField($builder, $options)
            ->addFirstNameField($builder, $options)
            ->addLastNameField($builder, $options)
            ->addCompanyField($builder)
            ->addAddress1Field($builder, $options)
            ->addAddress2Field($builder, $options)
            ->addAddress3Field($builder)
            ->addZipCodeField($builder, $options)
            ->addCityField($builder, $options)
            ->addIso2CodeField($builder, $options)
            ->addPhoneField($builder)
            ->addIsDefaultShippingField($builder)
            ->addIsDefaultBillingField($builder)
            ->addIdCustomerAddressField($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    public function addSalutationField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_SALUTATION, ChoiceType::class, [
            'choices' => array_flip([
                'Mr' => 'customer.salutation.mr',
                'Ms' => 'customer.salutation.ms',
                'Mrs' => 'customer.salutation.mrs',
                'Dr' => 'customer.salutation.dr',
            ]),
            'label' => 'profile.form.salutation',
            'required' => true,
            'trim' => true,
            'constraints' => [
                $this->createNotBlankConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFirstNameField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_FIRST_NAME, TextType::class, [
            'label' => 'customer.address.first_name',
            'required' => true,
            'trim' => true,
            'constraints' => [
                $this->createNotBlankConstraint($options),
                $this->createMinLengthConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addLastNameField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_LAST_NAME, TextType::class, [
            'label' => 'customer.address.last_name',
            'required' => true,
            'trim' => true,
            'constraints' => [
                $this->createNotBlankConstraint($options),
                $this->createMinLengthConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addCompanyField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_COMPANY, TextType::class, [
            'label' => 'customer.address.company',
            'required' => false,
            'trim' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAddress1Field(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_ADDRESS_1, TextType::class, [
            'label' => 'customer.address.address1',
            'required' => true,
            'trim' => true,
            'constraints' => [
                $this->createNotBlankConstraint($options),
                $this->createMinLengthConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addAddress2Field(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_ADDRESS_2, TextType::class, [
            'label' => 'customer.address.number',
            'required' => true,
            'trim' => true,
            'constraints' => [
                $this->createNotBlankConstraint($options),
                $this->createAddressNumberConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addAddress3Field(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ADDRESS_3, TextType::class, [
            'label' => 'customer.address.address3',
            'required' => false,
            'trim' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addZipCodeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_ZIP_CODE, TextType::class, [
            'label' => 'customer.address.zip_code',
            'required' => true,
            'trim' => true,
            'constraints' => [
                $this->createNotBlankConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCityField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_CITY, TextType::class, [
            'label' => 'customer.address.city',
            'required' => true,
            'trim' => true,
            'constraints' => [
                $this->createNotBlankConstraint($options),
                $this->createMinLengthConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIso2CodeField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(self::FIELD_ISO_2_CODE, ChoiceType::class, [
            'label' => 'customer.address.country',
            'required' => true,
            'choices' => array_flip($options[self::OPTION_COUNTRY_CHOICES]),
            'constraints' => [
                $this->createNotBlankConstraint($options),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addPhoneField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_PHONE, TelType::class, [
            'label' => 'customer.address.phone',
            'required' => false,
            'trim' => true,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsDefaultShippingField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_IS_DEFAULT_SHIPPING, CheckboxType::class, [
            'label' => 'customer.address.is_default_shipping',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIsDefaultBillingField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_IS_DEFAULT_BILLING, CheckboxType::class, [
            'label' => 'customer.address.is_default_billing',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCustomerAddressField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_CUSTOMER_ADDRESS, HiddenType::class);

        return $this;
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(array $options): NotBlank
    {
        return new NotBlank(['message' => static::VALIDATION_NOT_BLANK_MESSAGE]);
    }

    /**
     * @param array $options
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
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    protected function createZipCodeConstraint(array $options)
    {
        $validationGroup = $this->getValidationGroup($options);

        return new Regex([
            'pattern' => '/^\d{5}$/',
            'message' => static::VALIDATION_ZIP_CODE_MESSAGE,
            'groups' => $validationGroup,
        ]);
    }

    /**
     * @param array $options
     *
     * @return \Symfony\Component\Validator\Constraints\Regex
     */
    protected function createAddressNumberConstraint(array $options)
    {
        $validationGroup = $this->getValidationGroup($options);

        return new Regex([
            'pattern' => '/^\d+[a-zA-Z]*$/',
            'message' => static::VALIDATION_ADDRESS_NUMBER_MESSAGE,
            'groups' => $validationGroup,
        ]);
    }

    /**
     * @param array $options
     *
     * @return string
     */
    protected function getValidationGroup(array $options)
    {
        $validationGroup = Constraint::DEFAULT_GROUP;
        if (!empty($options['validation_group'])) {
            $validationGroup = $options['validation_group'];
        }

        return $validationGroup;
    }
}
