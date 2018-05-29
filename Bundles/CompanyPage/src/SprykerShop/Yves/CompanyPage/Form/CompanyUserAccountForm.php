<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyUserAccountForm extends AbstractType
{
    public const FIELD_COMPANY_USER_ACCOUNT_CHOICE = 'companyUserAccount';

    public const OPTION_COMPANY_USER_ACCOUNT_CHOICES = 'companyUserAccountChoices';

    public const FORM_NAME = 'companyUserAccount';

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
        $this->addCompanyUserAccountChoice($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    protected function addCompanyUserAccountChoice(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(static::FIELD_COMPANY_USER_ACCOUNT_CHOICE, ChoiceType::class, [
            'choices' => $options[static::OPTION_COMPANY_USER_ACCOUNT_CHOICES],
        ]);
    }
}
