<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\CheckoutPage\Form\Steps;

use Generated\Shared\Transfer\ShipmentGroupsTransfer;
use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * @method \SprykerShop\Yves\CheckoutPage\CheckoutPageConfig getConfig()
 */
class ShipmentGroupForm extends AbstractType
{

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'shipmentGroupForm';
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
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
            if (null !== $shipmentGroupTransfer) {
                if (isset($options[ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP][$shipmentGroupTransfer->getHash()])) {
                    $availableShipmentMethods = $options[ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP][$shipmentGroupTransfer->getHash()];
                }
                $form->add('shipment', ShipmentForm::class, [
                    ShipmentForm::OPTION_SHIPMENT_METHODS => $availableShipmentMethods ?? [],
                    'required' => true,
                ]);
            }
        });

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShipmentGroupTransfer::class,
        ]);
        $resolver->setRequired(ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP);
    }
}
