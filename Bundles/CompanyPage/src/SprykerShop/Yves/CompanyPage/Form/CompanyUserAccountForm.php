<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyUserAccountForm extends AbstractType
{
    public const FIELD_COMPANY_USER_ACCOUNT_CHOICE = 'companyUserAccount';
    public const FIELD_IS_DEFAULT = 'is_default';

    public const OPTION_COMPANY_USER_ACCOUNT_CHOICES = 'companyUserAccountChoices';

    public const FORM_NAME = 'company_user_account_form';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::FORM_NAME;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired([static::OPTION_COMPANY_USER_ACCOUNT_CHOICES]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCompanyUserAccountChoice($builder, $options)
            ->addIsDefaultCheckbox($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return self
     */
    protected function addCompanyUserAccountChoice(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_COMPANY_USER_ACCOUNT_CHOICE, ChoiceType::class, [
            'choices' => $options[static::OPTION_COMPANY_USER_ACCOUNT_CHOICES],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return self
     */
    protected function addIsDefaultCheckbox(FormBuilderInterface $builder, array $options): self
    {
        $builder->add(static::FIELD_IS_DEFAULT, CheckboxType::class, [
            'label' => 'company-user.remember-choice',
            'mapped' => false
        ]);

        return $this;
    }
}
