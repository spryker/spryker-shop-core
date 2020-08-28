<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Generated\Shared\Transfer\CompanyUnitAddressCollectionTransfer;
use Generated\Shared\Transfer\CompanyUnitAddressTransfer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageConfig getConfig()
 */
class CompanyBusinessUnitForm extends AbstractType
{
    public const FIELD_NAME = 'name';
    public const FIELD_EMAIL = 'email';
    public const FIELD_PHONE = 'phone';
    public const FIELD_EXTERNAL_URL = 'external_url';
    public const FIELD_FK_COMPANY = 'fk_company';
    public const FIELD_ID_COMPANY_BUSINESS_UNIT = 'id_company_business_unit';
    public const FIELD_COMPANY_UNIT_ADDRESSES = 'address_collection';
    public const FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT = 'fk_parent_company_business_unit';
    public const COMPANY_UNIT_ADDRESSES_KEY = 'company_unit_addresses';
    public const ID_COMPANY_UNIT_ADDRESS_KEY = 'id_company_unit_address';

    protected const VALIDATION_NOT_BLANK_MESSAGE = 'validation.not_blank';
    protected const VALIDATION_EMAIL_MESSAGE = 'validation.email';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'companyBusinessUnitForm';
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(static::FIELD_COMPANY_UNIT_ADDRESSES);
        $resolver->setRequired(static::FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this
            ->addIdCompanyBusinessUnitField($builder)
            ->addFkCompanyField($builder)
            ->addNameField($builder)
            ->addFkCompanyBusinessUnitField($builder, $options)
            ->addEmailField($builder)
            ->addPhoneField($builder)
            ->addExternalUrlField($builder)
            ->addCompanyUnitAddressField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addIdCompanyBusinessUnitField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_ID_COMPANY_BUSINESS_UNIT, HiddenType::class, [
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFkCompanyField(FormBuilderInterface $builder)
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
     *
     * @return $this
     */
    protected function addNameField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_NAME, TextType::class, [
            'label' => 'company.account.business_unit.name',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
            ],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addEmailField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_EMAIL, EmailType::class, [
            'label' => 'company.account.business_unit.email',
            'required' => true,
            'constraints' => [
                $this->createNotBlankConstraint(),
                $this->createEmailConstraint(),
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
            'label' => 'company.account.business_unit.phone',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addExternalUrlField(FormBuilderInterface $builder)
    {
        $builder->add(static::FIELD_EXTERNAL_URL, UrlType::class, [
            'label' => 'company.account.business_unit.external_url',
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\NotBlank
     */
    protected function createNotBlankConstraint(): NotBlank
    {
        return new NotBlank(['message' => static::VALIDATION_NOT_BLANK_MESSAGE]);
    }

    /**
     * @return \Symfony\Component\Validator\Constraints\Email
     */
    protected function createEmailConstraint(): Email
    {
        return new Email(['message' => static::VALIDATION_EMAIL_MESSAGE]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addFkCompanyBusinessUnitField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT, ChoiceType::class, [
            'placeholder' => 'company.account.form.fk_parent_company_bu.placeholder',
            'label' => 'company.account.choose_parent_company_business_unit',
            'choices' => array_flip($options[static::FIELD_FK_PARENT_COMPANY_BUSINESS_UNIT]),
            'required' => false,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyUnitAddressField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY_UNIT_ADDRESSES, ChoiceType::class, [
            'choices' => array_flip($options[static::FIELD_COMPANY_UNIT_ADDRESSES]),
            'required' => false,
            'expanded' => true,
            'multiple' => true,
        ]);

        $this->addCompanyUnitAddressTransformer($builder);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return void
     */
    protected function addCompanyUnitAddressTransformer(FormBuilderInterface $builder)
    {
        $builder->get(static::FIELD_COMPANY_UNIT_ADDRESSES)
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($addresses) {
                        if (empty($addresses)) {
                            return $addresses;
                        }

                        $result = [];
                        if (isset($addresses[static::COMPANY_UNIT_ADDRESSES_KEY])) {
                            foreach ($addresses[static::COMPANY_UNIT_ADDRESSES_KEY] as $address) {
                                $result[] = $address[static::ID_COMPANY_UNIT_ADDRESS_KEY];
                            }
                        }

                        return $result;
                    },
                    function ($data) {

                        $companyUnitAddressCollectionTransfer = new CompanyUnitAddressCollectionTransfer();

                        foreach ($data as $id) {
                            $companyUnitAddressTransfer = (new CompanyUnitAddressTransfer())->setIdCompanyUnitAddress($id);
                            $companyUnitAddressCollectionTransfer->addCompanyUnitAddress($companyUnitAddressTransfer);
                        }

                        return $companyUnitAddressCollectionTransfer;
                    }
                )
            );
    }
}
