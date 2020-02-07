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

/**
 * @method \SprykerShop\Yves\CompanyPage\CompanyPageConfig getConfig()
 */
class CompanyUserAccountSelectorForm extends AbstractType
{
    public const FIELD_COMPANY_USER_ACCOUNT_CHOICE = 'companyUserAccount';
    public const FIELD_IS_DEFAULT = 'is_default';

    public const OPTION_COMPANY_USER_ACCOUNT_CHOICES = 'companyUserAccountChoices';
    public const OPTION_COMPANY_USER_ACCOUNT_DEFAULT_SELECTED = 'companyUserAccountDefaultSelected';

    public const FORM_NAME = 'company_user_account_selector_form';

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
        $resolver->setRequired([static::OPTION_COMPANY_USER_ACCOUNT_DEFAULT_SELECTED]);
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
     * @return $this
     */
    protected function addCompanyUserAccountChoice(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY_USER_ACCOUNT_CHOICE, ChoiceType::class, [
            'label' => 'company_user.company_choice',
            'choices' => $options[static::OPTION_COMPANY_USER_ACCOUNT_CHOICES],
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIsDefaultCheckbox(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_IS_DEFAULT, CheckboxType::class, [
            'label' => 'company_user.remember_choice',
            'data' => $options[static::OPTION_COMPANY_USER_ACCOUNT_DEFAULT_SELECTED],
        ]);

        return $this;
    }
}
