<?php

/**
 * This file is part of the Spryker Demoshop.
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerShop\Yves\CompanyUserPage\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyUserAccountFrom extends AbstractType
{
    public const FIELD_COMPANY_USER_ACCOUNT_CHOICE = 'companyUserAccount';

    public const OPTION_COMPANY_USER_ACCOUNT_CHOICES = 'companyUserAccountChoices';

    public const FORM_NAME = 'companyUserAccount';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->addCompanyUserAccountChoice($builder, $options);
    }

    protected function addCompanyUserAccountChoice(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_COMPANY_USER_ACCOUNT_CHOICE, ChoiceType::class, [
            'choices' => $options[static::OPTION_COMPANY_USER_ACCOUNT_CHOICES],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([static::OPTION_COMPANY_USER_ACCOUNT_CHOICES]);
    }

    public function getName()
    {
        return static::FORM_NAME;
    }
}