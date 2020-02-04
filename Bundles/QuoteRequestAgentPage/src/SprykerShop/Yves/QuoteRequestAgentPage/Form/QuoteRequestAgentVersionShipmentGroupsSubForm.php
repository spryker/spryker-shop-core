<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\QuoteRequestAgentPage\Form;

use Generated\Shared\Transfer\ShipmentGroupTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageFactory getFactory()
 * @method \SprykerShop\Yves\QuoteRequestAgentPage\QuoteRequestAgentPageConfig getConfig()
 */
class QuoteRequestAgentVersionShipmentGroupsSubForm extends AbstractType
{
    /**
     * @param \Symfony\Component\OptionsResolver\OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ShipmentGroupTransfer::class,
            'label' => false,
        ]);
        $resolver->setRequired([
            QuoteRequestAgentForm::OPTION_PRICE_MODE,
        ]);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $this->addShipmentForm($builder, $options);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            ShipmentGroupTransfer::SHIPMENT,
            QuoteRequestAgentShipmentSubForm::class,
            [
                QuoteRequestAgentForm::OPTION_PRICE_MODE => $options[QuoteRequestAgentForm::OPTION_PRICE_MODE],
            ]
        );

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            return $this->copySubmittedShipmentMethodPricesToItemShipmentMethods($event);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormEvent $event
     *
     * @return \Symfony\Component\Form\FormEvent
     */
    protected function copySubmittedShipmentMethodPricesToItemShipmentMethods(FormEvent $event): FormEvent
    {
        /** @var \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer */
        $shipmentGroupTransfer = $event->getData();

        $shipmentMethodSourcePrice = $shipmentGroupTransfer->getShipment()->getMethod()->getSourcePrice();

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $itemTransfer->getShipment()->getMethod()->setSourcePrice($shipmentMethodSourcePrice);
        }

        $event->setData($shipmentGroupTransfer);

        return $event;
    }
}
