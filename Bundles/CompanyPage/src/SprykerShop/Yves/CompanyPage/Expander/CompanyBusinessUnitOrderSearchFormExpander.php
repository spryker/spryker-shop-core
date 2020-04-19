<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Expander;

use SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyBusinessUnitOrderSearchFormDataProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyBusinessUnitOrderSearchFormExpander implements CompanyBusinessUnitOrderSearchFormExpanderInterface
{
    public const OPTION_COMPANY_BUSINESS_UNIT_CHOICES = 'OPTION_COMPANY_BUSINESS_UNIT_CHOICES';

    /**
     * @uses \Spryker\Zed\CompanyBusinessUnitSalesConnector\CompanyBusinessUnitSalesConnectorConfig::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT
     */
    protected const FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT = 'companyBusinessUnit';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\OrderSearchForm::FIELD_FILTERS
     */
    protected const FIELD_FILTERS = 'filters';

    /**
     * @var \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyBusinessUnitOrderSearchFormDataProvider
     */
    protected $companyBusinessUnitOrderSearchFormDataProvider;

    /**
     * @param \SprykerShop\Yves\CompanyPage\Form\DataProvider\CompanyBusinessUnitOrderSearchFormDataProvider $companyBusinessUnitOrderSearchFormDataProvider
     */
    public function __construct(CompanyBusinessUnitOrderSearchFormDataProvider $companyBusinessUnitOrderSearchFormDataProvider)
    {
        $this->companyBusinessUnitOrderSearchFormDataProvider = $companyBusinessUnitOrderSearchFormDataProvider;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function expand(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder
            ->get(static::FIELD_FILTERS)
            ->add(
                static::FILTER_FIELD_TYPE_COMPANY_BUSINESS_UNIT,
                ChoiceType::class,
                [
                    'choices' => $this->companyBusinessUnitOrderSearchFormDataProvider->getOptions()[static::OPTION_COMPANY_BUSINESS_UNIT_CHOICES],
                    'required' => false,
                    'placeholder' => false,
                    'label' => 'company_business_unit_widget.business_unit',
                    'attr' => [
                        'class' => 'form__field col col--sm-12 col--lg-6',
                    ],
                ]
            );

        return $builder;
    }
}
