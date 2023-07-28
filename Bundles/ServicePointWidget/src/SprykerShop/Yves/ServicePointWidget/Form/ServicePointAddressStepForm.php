<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerShop\Yves\ServicePointWidget\Form;

use Spryker\Yves\Kernel\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * @method \SprykerShop\Yves\ServicePointWidget\ServicePointWidgetFactory getFactory()
 */
class ServicePointAddressStepForm extends AbstractType
{
    /**
     * @var string
     */
    public const FIELD_SERVICE_POINT = 'servicePoint';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\CheckoutAddressCollectionForm::FIELD_SHIPPING_ADDRESS
     *
     * @var string
     */
    protected const FIELD_SHIPPING_ADDRESS = 'shippingAddress';

    /**
     * @uses \SprykerShop\Yves\CustomerPage\Form\AddressForm::EXTRA_FIELD_SKIP_VALIDATION
     *
     * @var string
     */
    protected const EXTRA_FIELD_SKIP_VALIDATION = 'skip_validation';

    /**
     * @uses \SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeAddressStepForm::FIELD_SHIPMENT_TYPE
     *
     * @var string
     */
    protected const FIELD_SHIPMENT_TYPE = 'shipmentType';

    /**
     * @uses \Spryker\Shared\ShipmentType\ShipmentTypeConfig::SHIPMENT_TYPE_DELIVERY
     *
     * @var string
     */
    protected const SHIPMENT_TYPE_DELIVERY = 'delivery';

    /**
     * @uses \SprykerShop\Yves\ShipmentTypeWidget\Form\ShipmentTypeSubForm::FIELD_SHIPMENT_TYPE_KEY
     *
     * @var string
     */
    protected const FIELD_SHIPMENT_TYPE_KEY = 'key';

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array<string, mixed> $options
     *
     * @return \Symfony\Component\Form\FormBuilderInterface
     */
    public function buildForm(FormBuilderInterface $builder, array $options): FormBuilderInterface
    {
        $this
            ->addServicePointSubForm($builder)
            ->skipAddressFormValidation($builder);

        return $builder;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function addServicePointSubForm(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event): void {
            $this->getFactory()->createServicePointFormPreSetDataHydrator()->hydrate($event);
        });
        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event): void {
            $this->getFactory()->createServicePointFormSubmitDataHydrator()->hydrate($event);
        });

        return $this;
    }

    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     *
     * @return $this
     */
    protected function skipAddressFormValidation(FormBuilderInterface $builder)
    {
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event): void {
            $data = $event->getData();
            if (isset($data[static::FIELD_SHIPPING_ADDRESS]) && !$this->isShipmentTypeDelivery($data)) {
                $data[static::FIELD_SHIPPING_ADDRESS][static::EXTRA_FIELD_SKIP_VALIDATION] = true;
            }

            $event->setData($data);
        });

        return $this;
    }

    /**
     * @param array<string, mixed> $data
     *
     * @return bool
     */
    protected function isShipmentTypeDelivery(array $data): bool
    {
        $shipmentType = $data[static::FIELD_SHIPMENT_TYPE][static::FIELD_SHIPMENT_TYPE_KEY] ?? null;
        if ($shipmentType === null) {
            return true;
        }

        return $shipmentType === static::SHIPMENT_TYPE_DELIVERY;
    }
}
