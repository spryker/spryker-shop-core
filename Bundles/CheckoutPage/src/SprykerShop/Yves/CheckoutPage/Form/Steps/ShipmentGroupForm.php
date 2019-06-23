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
    public const FIELD_SHIPMENT = 'shipment';
    public const OPTION_SHIPMENT_GROUP_TRANSFER = 'shipmentGroupTransfer';
    public const OPTION_SHIPMENT_LABEL = 'shipmentLabel';

    /**
     * @var \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected $shipmentGroupTransfer;

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
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->shipmentGroupTransfer = $this->findShipmentGroupTransfer($builder, $options);
        if ($this->shipmentGroupTransfer === null) {
            return;
        }

        $this
            ->addShipmentSubForm($builder, $options)
            ->addFormSubmitEventListener($builder);
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return $this
     */
    protected function addShipmentSubForm(FormBuilderInterface $builder, array $options)
    {
        $shipmentGroupHash = $this->shipmentGroupTransfer->getHash();
        $availableShipmentMethods = $options[ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP][$shipmentGroupHash] ?? [];
        $shippingAddressLabel = $options[ShipmentCollectionForm::OPTION_SHIPMENT_ADDRESS_LABEL_LIST][$shipmentGroupHash] ?? '';

        $builder->add(static::FIELD_SHIPMENT, MultiShipmentForm::class, [
            'required' => true,
            'label' => $shippingAddressLabel,
            MultiShipmentForm::OPTION_SHIPMENT_METHODS => $availableShipmentMethods,
        ]);

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addFormSubmitEventListener(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) {
            $shipmentGroupTransfer = $event->getData();
            if (!($shipmentGroupTransfer instanceof ShipmentGroupTransfer)) {
                return;
            }

            $shipmentGroupTransfer = $this->mapSubmittedShipmentSubFormDataToItemLevelShipments($shipmentGroupTransfer);
            $event->setData($shipmentGroupTransfer);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array $options
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer|null
     */
    protected function findShipmentGroupTransfer(FormBuilderInterface $builder, array $options): ?ShipmentGroupTransfer
    {
        /** @var \ArrayObject|\Generated\Shared\Transfer\ShipmentGroupTransfer[] $shipmentGroupCollection */
        $shipmentGroupCollection = $options[ShipmentCollectionForm::OPTION_SHIPMENT_GROUPS];
        $shipmentGroupIndex = $this->findShipmentGroupIndex($builder);

        if (!isset($shipmentGroupCollection[$shipmentGroupIndex])) {
            return null;
        }

        return $shipmentGroupCollection[$shipmentGroupIndex];
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return string|null
     */
    protected function findShipmentGroupIndex(FormBuilderInterface $builder): ?string
    {
        $propertyPath = $builder->getPropertyPath();
        if ($propertyPath === null) {
            return null;
        }

        return $propertyPath->getElement(0);
    }

    /**
     * @param \Generated\Shared\Transfer\ShipmentGroupTransfer $shipmentGroupTransfer
     *
     * @return \Generated\Shared\Transfer\ShipmentGroupTransfer
     */
    protected function mapSubmittedShipmentSubFormDataToItemLevelShipments(
        ShipmentGroupTransfer $shipmentGroupTransfer
    ): ShipmentGroupTransfer {
        $shipmentTransfer = $shipmentGroupTransfer->getShipment();
        if ($shipmentTransfer === null) {
            return $shipmentGroupTransfer;
        }

        foreach ($shipmentGroupTransfer->getItems() as $itemTransfer) {
            $itemTransfer->setShipment($shipmentTransfer);
        }

        return $shipmentGroupTransfer;
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
            ->setRequired(ShipmentCollectionForm::OPTION_SHIPMENT_GROUPS)
            ->setRequired(ShipmentCollectionForm::OPTION_SHIPMENT_METHODS_BY_GROUP)
            ->setRequired(ShipmentCollectionForm::OPTION_SHIPMENT_ADDRESS_LABEL_LIST);
    }
}
