<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyBusinessUnitForm extends AbstractType
{
    public const OPTION_COMPANY_BUSINESS_UNIT_CHOICES = 'OPTION_COMPANY_BUSINESS_UNIT_CHOICES';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitSalesConnector\Business\Expander\OrderSearchQueryExpander::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT
     */
    protected const FIELD_COMPANY_BUSINESS_UNIT = 'companyBusinessUnit';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setRequired(static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addCompanyBusinessUnitField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addCompanyBusinessUnitField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            static::FIELD_COMPANY_BUSINESS_UNIT,
            ChoiceType::class,
            [
                'choices' => $options[static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES],
                'required' => false,
                'placeholder' => false,
                'label' => 'company_business_unit_widget.business_unit',
            ]
        );

        return $this;
    }
}
