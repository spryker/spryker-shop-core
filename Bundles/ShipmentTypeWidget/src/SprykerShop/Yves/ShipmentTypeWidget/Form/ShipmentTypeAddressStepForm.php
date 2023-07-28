<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ShipmentTypeWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\ShipmentTypeWidget\ShipmentTypeWidgetFactory getFactory()
 */
class ShipmentTypeAddressStepForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_SHIPMENT_TYPE = 'shipmentType';

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver->setDefined([
            ShipmentTypeSubForm::OPTION_SHIPMENT_TYPES,
            ShipmentTypeSubForm::OPTION_AVAILABLE_SHIPMENT_TYPES,
        ]);
        $resolver->setRequired([
            ShipmentTypeSubForm::OPTION_SHIPMENT_TYPES,
            ShipmentTypeSubForm::OPTION_AVAILABLE_SHIPMENT_TYPES,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this->addShipmentTypeSubForm($builder, $options);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return void
     */
    protected function addShipmentTypeSubForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options): void {
            $this->getFactory()->createShipmentTypeFormPreSetDataHydrator()->hydrate($event, $options);
        });
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options): void {
            $this->getFactory()->createShipmentTypeFormSubmitDataHydrator()->hydrate($event, $options);
        });
    }
}
