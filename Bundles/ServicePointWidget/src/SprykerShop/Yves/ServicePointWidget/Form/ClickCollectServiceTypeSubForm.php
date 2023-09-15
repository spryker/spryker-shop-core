<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Form;

use Generated\Shared\Transfer\ShipmentTypeTransfer;
use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;

class ClickCollectServiceTypeSubForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_PICKUPABLE_SERVICE_TYPE = 'pickupableServiceType';

    /**
     * @var string
     */
    public const ATTRIBUTE_DATA_SERVICE_TYPE_UUID = 'data-service-type-uuid';

    /**
     * @var string
     */
    public const ATTRIBUTE_DATA_SHIPMENT_TYPE_UUID = 'data-shipment-type-uuid';

    /**
     * @uses \SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeSubForm::OPTION_AVAILABLE_SHIPMENT_TYPES
     *
     * @var string
     */
    protected const OPTION_AVAILABLE_SHIPMENT_TYPES = 'option_available_shipment_types';

    /**
     * @uses \Spryker\Shared\ServicePoint\ServicePointConfig::SERVICE_TYPE_PICKUP
     *
     * @var string
     */
    protected const SERVICE_TYPE_PICKUP = 'pickup';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options): void {
            $form = $event->getForm();
            $servicePointForm = $this->findServicePointForm($form);

            if (!$servicePointForm) {
                return;
            }

            $shipmentTypeTransfer = $this->findShipmentTypeByPickupServiceType($options);

            if (!$shipmentTypeTransfer) {
                return;
            }

            $servicePointForm->add(static::FIELD_PICKUPABLE_SERVICE_TYPE, HiddenType::class, [
                'mapped' => false,
                'data' => $shipmentTypeTransfer->getServiceTypeOrFail()->getKeyOrFail(),
                'attr' => [
                    static::ATTRIBUTE_DATA_SHIPMENT_TYPE_UUID => $shipmentTypeTransfer->getUuidOrFail(),
                    static::ATTRIBUTE_DATA_SERVICE_TYPE_UUID => $shipmentTypeTransfer->getServiceTypeOrFail()->getUuidOrFail(),
                ],
            ]);
        });

        return $builder;
    }

    /**
     * @param array<string, mixed> $options
     *
     * @return \Generated\Shared\Transfer\ShipmentTypeTransfer|null
     */
    protected function findShipmentTypeByPickupServiceType(array $options): ?ShipmentTypeTransfer
    {
        /** @var array<string, \Generated\Shared\Transfer\ShipmentTypeTransfer>|null $availableShipmentTypes */
        $availableShipmentTypes = $options[static::OPTION_AVAILABLE_SHIPMENT_TYPES] ?? null;
        if (!$availableShipmentTypes) {
            return null;
        }

        foreach ($availableShipmentTypes as $shipmentTypeTransfer) {
            if ($shipmentTypeTransfer->getServiceType() && $shipmentTypeTransfer->getServiceTypeOrFail()->getKeyOrFail() === static::SERVICE_TYPE_PICKUP) {
                return $shipmentTypeTransfer;
            }
        }

        return null;
    }

    /**
     * @param \Symfony\Component\Form\FormInterface $form
     *
     * @return \Symfony\Component\Form\FormInterface|null
     */
    protected function findServicePointForm(FormInterface $form): ?FormInterface
    {
        return $form->has(ServicePointAddressStepForm::FIELD_SERVICE_POINT) ? $form->get(ServicePointAddressStepForm::FIELD_SERVICE_POINT) : null;
    }
}
