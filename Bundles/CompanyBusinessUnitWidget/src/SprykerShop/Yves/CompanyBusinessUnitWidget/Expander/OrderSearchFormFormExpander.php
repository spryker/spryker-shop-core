<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyBusinessUnitWidget\Expander;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

class OrderSearchFormFormExpander implements OrderSearchFormFormExpanderInterface
{
    protected const FIELD_BUSINESS_UNIT = 'businessUnit';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function expandOrderSearchFormWithBusinessUnitField(FormBuilderInterface $builder, array $options): void
    {
        $builder->add(
            static::FIELD_BUSINESS_UNIT,
            ChoiceType::class,
            [
                'choices' => [],
                'required' => false,
                'label' => 'company_business_unit_widget.business_unit',
            ]
        );
    }
}
