<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class ShipmentGroupForm extends AbstractType
{
    public const BLOCK_PREFIX = 'shipmentGroupForm';

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return static::BLOCK_PREFIX;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addShipmentMethods($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentMethods(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            /**
             * @var \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
             */
            $shipmentGroupTransfer = $event->getData();
            $form = $event->getForm();
            $options = $form->getConfig()->getOptions();
            if ($shipmentGroupTransfer instanceof ShipmentGroupTransfer) {
                $availableShipmentMethods = $options[ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP][$shipmentGroupTransfer->getHash()] ?? [];
                $shippingAddressLabel = $options[ShipmentCollectionForm::OPTION_SHIPMENT_ADDRESS_LABEL_LIST][$shipmentGroupTransfer->getHash()] ?? '';

                $form->add('shipment', MultiShipmentForm::class, [
                    MultiShipmentForm::OPTION_SHIPMENT_METHODS => $availableShipmentMethods,
                    'required' => true,
                    'label' => $shippingAddressLabel,
                ]);
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            /**
             * @var \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
             */
            $shipmentGroupTransfer = $event->getForm()->getData();
            if ($shipmentGroupTransfer instanceof ShipmentGroupTransfer) {
                $shipmentTransfer = $shipmentGroupTransfer->getShipment();
                foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
                    $itemTransfer->setShipment($shipmentTransfer);
                }
            }
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefaults([
                'data_class' => ShipmentGroupTransfer::class,
            ])
            ->setRequired(ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP)
            ->setRequired(ShipmentCollectionForm::OPTION_SHIPMENT_ADDRESS_LABEL_LIST);
    }
}
