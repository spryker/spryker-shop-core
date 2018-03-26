<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\SharedCartPage\Form;

use Generated\Shared\Transfer\ShareCartRequestTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ShareCartForm extends AbstractType
{
    public const FORM_NAME = 'shareCartForm';
    public const FIELD_ID_QUOTE = 'idQuote';
    public const FIELD_COMPANY_USER_ID = 'idCompanyUser';
    public const FIELD_QUOTE_PERMISSION_GROUP_ID = 'idQuotePermissionGroup';
    public const OPTION_CUSTOMERS = 'OPTION_CUSTOMERS';
    public const OPTION_PERMISSION_GROUPS = 'OPTION_PERMISSION_GROUPS';

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return self::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(self::OPTION_CUSTOMERS);
        $resolver->setRequired(self::OPTION_PERMISSION_GROUPS);
        $resolver->setDefaults([
            'data_class' => ShareCartRequestTransfer::class,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addQuoteIdField($builder);
        $this->addCompanyUserIdField($builder, $options);
        $this->addQuotePermissionGroupIdQuoteIdField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addQuoteIdField(FormBuilderInterface $builder)
    {
        $builder->add(self::FIELD_ID_QUOTE, HiddenType::class, []);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyUserIdField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY_USER_ID, ChoiceType::class, [
            'choices' => array_flip($options[static::OPTION_CUSTOMERS]),
            'choices_as_values' => true,
            'expanded' => false,
            'required' => true,
            'placeholder' => 'shared_cart.form.select_customer',
            'constraints' => [
                new NotBlank(),
            ],
            'label' => 'shared_cart.form.customer',
            ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addQuotePermissionGroupIdQuoteIdField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_QUOTE_PERMISSION_GROUP_ID, ChoiceType::class, [
            'choices' => array_flip($options[static::OPTION_PERMISSION_GROUPS]),
            'choices_as_values' => true,
            'expanded' => true,
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
            'label' => 'shared_cart.form.select_permissions',
            ]);

        return $this;
    }
}
