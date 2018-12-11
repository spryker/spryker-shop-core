<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CompanyPage\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class MultiCurrencyType extends AbstractType
{
    protected const FIELD_MULTI_CURRENCY = 'multi_currency';

    /**
     * @return string
     */
    public function getName(): string
    {
        return 'MultiCurrencyType';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var \Generated\Shared\Transfer\PermissionTransfer $PermissionTransfer */
            $permissionTransfer = $event->getForm()->getParent()->getData();
            $permissionTransferConfiguration = $permissionTransfer->getConfiguration();
            $currencyIsoCodes = $this->getFactory()->getStore()->getCurrencyIsoCodes();

            foreach ($currencyIsoCodes as $currencyIsoCode) {
                $data = $permissionTransferConfiguration[static::FIELD_MULTI_CURRENCY][$currencyIsoCode] ?? null;

                $form->add($currencyIsoCode, NumberType::class, [
                    'data' => $data,
                    'attr' => ['placeholder' => 'permission.name.PlaceOrderPermissionPlugin.cent_amount'],
                    'label' => strtoupper($currencyIsoCode),
                ]);
            }
        });
    }
}
