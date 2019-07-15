<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class CompanyBusinessUnitAddressForm extends CompanyUnitAddressForm
{
    public const FIELD_IS_DEFAULT_BILLING = 'is_default_billing';

    protected const FIELD_LABEL_IS_DEFAULT_BILLING = 'company.account.address.is_default_billing';

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

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            $this->getCompanyBusinessUnitAddressIsDefaultBillingFormPreSetDataCallback()
        );
    }

    /**
     * @return \Closure
     */
    protected function getCompanyBusinessUnitAddressIsDefaultBillingFormPreSetDataCallback(): callable
    {
        return function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            $form->add(static::FIELD_IS_DEFAULT_BILLING, CheckboxType::class, [
                'required' => false,
                'label' => static::FIELD_LABEL_IS_DEFAULT_BILLING,
                'disabled' => isset($data[static::FIELD_IS_DEFAULT_BILLING]),
            ]);
        };
    }
}
