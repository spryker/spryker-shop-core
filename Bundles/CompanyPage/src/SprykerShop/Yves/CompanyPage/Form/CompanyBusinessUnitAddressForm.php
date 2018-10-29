<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;

class CompanyBusinessUnitAddressForm extends CompanyUnitAddressForm
{
    public const FIELD_IS_DEFAULT_BILLING = 'is_default_billing';
    public const FIELD_IS_DEFAULT_SHIPPING = 'is_default_shipping';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'CompanyBusinessUnitAddressForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $this->addIsDefaultBillingField($builder, $options);
        $this->addIsDefaultShippingField($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addIsDefaultBillingField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_IS_DEFAULT_BILLING, CheckboxType::class, [
            'label' => 'company.account.address.is_default_billing',
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
    protected function addIsDefaultShippingField(FormBuilderInterface $builder, array $options)
    {
        $builder->add(static::FIELD_IS_DEFAULT_SHIPPING, CheckboxType::class, [
            'label' => 'company.account.address.is_default_shipping',
            'required' => false,
        ]);
        return $this;
    }
}
